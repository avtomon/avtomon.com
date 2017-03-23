<?php
/**
 * ABC_RSS generates and exports all required and optional elements of an RSS 2.0 feed. 
 * 
 * 
 * This class has two setter methods which take arrays as parameters. The class parses the
 * arrays for all required and all optional RSS 2.0 elements. Additional namespaces are not
 * supported in this version.
 * 
 * The class attempts to translate input characters into html entities in most cases, but it
 * does tolerate (X)HTML markup in 'description' tags, and wraps the content of description
 * tags in a CDATA section. It also enforces integer values where integer values are expected,
 * and enforces required attributes and sub-elements in optional elements.
 * 
 * The output XML can be written to file, or displayed in a browser, and it is also possible
 * to "force" a browser to download the XML file rather than to display it.
 * 
 * 
 * @author Richard Lucas <webmaster@applebiter.com>
 * @category RSS
 * @version 0.1
 * @copyright Copyright (c) 2010 Richard Lucas 
 * @example example.php
 * @license http://www.opensource.org/licenses/mit-license.php
 * @link http://cyber.law.harvard.edu/rss/rss.html
 * @todo Add namespacing
 * 
 * 
 * 
 * The MIT License
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 	
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */
class ABC_RSS {
	
	/**
	 * This array holds the formatted channel element data
	 *
	 * @var array
	 */
	public $channel =array();
	
	/**
	 * This array is a que of all of the inserted item arrays
	 *
	 * @var array
	 */
	public $items =array();
	
	/**
	 * This array holds errors and can be accessed 
	 *
	 * @var array
	 */
	public $errors =array();
	
	/**
	 * The XML/RSS content
	 *
	 * @var string
	 */
	public $rss;
	
	/**
	 * Set the channel data and validate it all at once
	 *
	 * @param array mixed $chnl
	 * @return boolean true on success, false on error
	 */
	public function set_channel( $chnl =array() )
	{
		try {
			
			if ( count( $chnl ) < 1 ) 
				throw new Exception( "The input array was empty." );
			
			/**
			 * First transfer the values from the input array to a new array. This is done to ensure
			 * that the item array structure is valid, to make sure that the user input is properly
			 * escaped, and to make it easier to validate the elements for required attributes and
			 * sub-elements at the end.
			 */
			
			// Required
			$this->channel =array(); 
			
			// Required
			$this->channel['title'] =$this->prep( $chnl, 'title', TRUE, FALSE );
			
			// Required
			$this->channel['link'] =$this->prep( $chnl, 'link', TRUE, FALSE );
			
			// Required
			$this->channel['description'] =$this->prep( $chnl, 'description', TRUE, FALSE );
			
			// Optional
			$this->channel['language'] =$this->prep( $chnl, 'language', TRUE, FALSE );
			
			// Optional
			$this->channel['copyright'] =$this->prep( $chnl, 'copyright', TRUE, FALSE );
			
			// Optional
			$this->channel['managingEditor'] =$this->prep( $chnl, 'managingEditor', TRUE, FALSE );
			
			// Optional
			$this->channel['webMaster'] =$this->prep( $chnl, 'webMaster', TRUE, FALSE );
			
			// Optional
			$this->channel['pubDate'] =$this->prep( $chnl, 'pubDate', TRUE, FALSE );
			
			// Optional
			$this->channel['lastBuildDate'] =$this->prep( $chnl, 'lastBuildDate', TRUE, FALSE );
			
			// Optional
			$this->channel['category'] =( array_key_exists( 'category', $chnl ) && is_array( $chnl['category'] )) ? array() : NULL; 			
			
			// Required if <category> is used
			if ( is_array( $this->channel['category'] ))
			{
				for ( $i =0; $i <count( $chnl['category'] ) ; $i++)
				{
					$this->channel['category'][$i]['content'] =$this->prep( $chnl['category'][$i], 'content', TRUE, FALSE );
					
					if ( count( $chnl['category'][$i] ) >1 )
						$this->channel['category'][$i]['domain'] =$this->prep( $chnl['category'][$i], 'domain', TRUE, FALSE );
				}
			}	
			
			// Optional
			$this->channel['cloud'] =( array_key_exists( 'cloud', $chnl ) && is_array( $chnl['cloud'] )) ? array() : NULL; 			
			
			// Required if <cloud> is used
			if ( is_array( $this->channel['cloud'] ))
				$this->channel['cloud']['domain'] =$this->prep( $chnl['cloud'], 'domain', TRUE, FALSE );
			
			// Required if <cloud> is used
			if ( is_array( $this->channel['cloud'] ))
				$this->channel['cloud']['port'] =$this->prep( $chnl['cloud'], 'port', TRUE, TRUE );
			
			// Required if <cloud> is used
			if ( is_array( $this->channel['cloud'] ))
				$this->channel['cloud']['path'] =$this->prep( $chnl['cloud'], 'path', TRUE, FALSE );
			
			// Required if <cloud> is used
			if ( is_array( $this->channel['cloud'] ))
				$this->channel['cloud']['registerProcedure'] =$this->prep( $chnl['cloud'], 'registerProcedure', TRUE, FALSE );
			
			// Required if <cloud> is used
			if ( is_array( $this->channel['cloud'] ))
				$this->channel['cloud']['protocol'] =$this->prep( $chnl['cloud'], 'protocol', TRUE, FALSE );
			
			// Optional
			$this->channel['docs'] =$this->prep( $chnl, 'docs', TRUE, FALSE );
			
			// Optional
			$this->channel['generator'] =$this->prep( $chnl, 'generator', TRUE, FALSE );
			
			// Optional
			$this->channel['ttl'] =$this->prep( $chnl, 'ttl', TRUE, TRUE );
			
			// Optional
			$this->channel['rating'] =$this->prep( $chnl, 'rating', FALSE, FALSE);
			
			// Optional
			$this->channel['textInput'] =( array_key_exists( 'textInput', $chnl ) && is_array( $chnl['textInput'] )) ? array() : NULL; 	
			
			// Required if <textInput> is used
			if ( is_array( $this->channel['textInput'] ))
				$this->channel['textInput']['title'] =$this->prep( $chnl['textInput'], 'title', TRUE, FALSE );
			
			// Required if <textInput> is used
			if ( is_array( $this->channel['textInput'] ))
				$this->channel['textInput']['description'] =$this->prep( $chnl['textInput'], 'description', TRUE, FALSE );
			
			// Required if <textInput> is used
			if ( is_array( $this->channel['textInput'] ))
				$this->channel['textInput']['name'] =$this->prep( $chnl['textInput'], 'name', TRUE, FALSE );
			
			// Required if <textInput> is used
			if ( is_array( $this->channel['textInput'] ))
				$this->channel['textInput']['link'] =$this->prep( $chnl['textInput'], 'link', TRUE, FALSE );
			
			// Optional
			$this->channel['image'] =( array_key_exists( 'image', $chnl ) && is_array( $chnl['image'] )) ? array() : NULL; 	
			
			// Required if <image> is used
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['url'] =$this->prep( $chnl['image'], 'url', TRUE, FALSE );
			
			// Required if <image> is used
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['title'] =$this->prep( $chnl['image'], 'title', TRUE, FALSE );
			
			// Required if <image> is used
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['link'] =$this->prep( $chnl['image'], 'link', TRUE, FALSE );
			
			// Optional (Maximum: 144, Default: 88)
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['width'] =$this->prep( $chnl['image'], 'width', TRUE, TRUE );
			
			// Optional (Maximum: 400, Default: 31)
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['height'] =$this->prep( $chnl['image'], 'height', TRUE, TRUE );
			
			// Optional
			if ( is_array( $this->channel['image'] ))
				$this->channel['image']['description'] =$this->prep( $chnl['image'], 'description', TRUE, FALSE );
			
			// Optional
			$this->channel['skipHours'] =$this->prep( $chnl, 'skipHours', TRUE, FALSE );
			
			// Optional
			$this->channel['skipDays'] =$this->prep( $chnl, 'skipDays', TRUE, FALSE );
			
			/**
			 * Next, validate the channel for required elements, sub-elements, and attributes
			 */
			
			if ( empty( $this->channel['title'] ))
				throw new Exception( "The channel 'title' tag must not be empty." );
			
			if ( empty( $this->channel['description'] ))
				throw new Exception( "The channel 'description' tag must not be empty." );
			
			if ( empty( $this->channel['link'] ))
				throw new Exception( "The channel 'link' tag must not be empty." );
			
			if ( is_array( $this->channel['category'] ))
				for ( $i =0 ; $i <count( $this->channel['category'] ) ; $i++ )
					if ( empty( $this->channel['category'][$i]['content'] ))
						throw new Exception( "The channel 'category' tag must not be empty if it is used." );
			
			if ( is_array( $this->channel['cloud'] ) && empty ( $this->channel['cloud']['domain'] ))
				throw new Exception( "The cloud 'domain' attribute cannot be empty." );
			
			if ( is_array( $this->channel['cloud'] ) && empty ( $this->channel['cloud']['port'] ))
				throw new Exception( "The cloud 'port' attribute cannot be empty." );
			
			if ( is_array( $this->channel['cloud'] ) && empty ( $this->channel['cloud']['path'] ))
				throw new Exception( "The cloud 'path' attribute cannot be empty." );
			
			if ( is_array( $this->channel['cloud'] ) && empty ( $this->channel['cloud']['registerProcedure'] ))
				throw new Exception( "The cloud 'registerProcedure' attribute cannot be empty." );
			
			if ( is_array( $this->channel['cloud'] ) && empty ( $this->channel['cloud']['protocol'] ))
				throw new Exception( "The cloud 'protocol' attribute cannot be empty." );
			
			if ( is_array( $this->channel['textInput'] ) && empty ( $this->channel['textInput']['title'] ))
				throw new Exception( "The textInput 'title' attribute cannot be empty." );
			
			if ( is_array( $this->channel['textInput'] ) && empty ( $this->channel['textInput']['description'] ))
				throw new Exception( "The textInput 'description' attribute cannot be empty." );
			
			if ( is_array( $this->channel['textInput'] ) && empty ( $this->channel['textInput']['name'] ))
				throw new Exception( "The textInput 'name' attribute cannot be empty." );
			
			if ( is_array( $this->channel['textInput'] ) && empty ( $this->channel['textInput']['link'] ))
				throw new Exception( "The textInput 'link' attribute cannot be empty." );
			
			if ( is_array( $this->channel['image'] ) && empty ( $this->channel['image']['url'] ))
				throw new Exception( "The image 'url' attribute cannot be empty." );
			
			if ( is_array( $this->channel['image'] ) && empty ( $this->channel['image']['title'] ))
				throw new Exception( "The image 'title' attribute cannot be empty." );
			
			if ( is_array( $this->channel['image'] ) && empty ( $this->channel['image']['link'] ))
				throw new Exception( "The image 'link' attribute cannot be empty." );
			
			return TRUE;
		}
		
		catch ( Exception $e ) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * Set each item's data and validate it all at once
	 *
	 * @param array mixed $itm
	 * @return boolean true on success, false on error
	 */
	public function set_item( $itm =array() )
	{
		try {
			
			if ( count( $itm ) < 1 )
				throw new Exception( "The input array was empty." );
			
			/**
			 * First transfer the values from the input array to a new array. This is done to ensure
			 * that the item array structure is valid, to make sure that the user input is properly
			 * escaped, and to make it easier to validate the elements for required attributes and
			 * sub-elements at the end.
			 */
			
			// At least one is required
			$item = array(); 
			
			// Either the title or the description is required, though not both
			$item['title'] =$this->prep( $itm, 'title', TRUE, FALSE );
			
			// Optional
			$item['link'] =$this->prep( $itm, 'link', TRUE, FALSE );
			
			// Either the title or the description is required, though not both
			$item['description'] =$this->prep( $itm, 'description', TRUE, FALSE );
			
			// Optional
			$item['author'] =$this->prep( $itm, 'author', TRUE, FALSE );
			
			// Optional
			$item['category'] =( array_key_exists( 'category', $itm ) && is_array( $itm['category'] )) ? array() : NULL; 			
			
			// Required if <category> is used
			if ( is_array( $item['category'] ))
			{
				for ( $i =0; $i <count( $itm['category'] ) ; $i++)
				{
					$item['category'][$i]['content'] =$this->prep( $itm['category'][$i], 'content', TRUE, FALSE );
					
					if ( count( $itm['category'][$i] ) >1 )
						$item['category'][$i]['domain'] =$this->prep( $itm['category'][$i], 'domain', TRUE, FALSE );
				}
			}
			
			// Optional
			$item['comments'] =$this->prep( $itm, 'comments', TRUE, FALSE );
			
			// Optional
			$item['enclosure'] =( array_key_exists( 'enclosure', $itm ) && is_array( $itm['enclosure'] )) ? array() : NULL; 
			
			// Required if <enclosure> is used
			if ( is_array( $item['enclosure'] ))
				$item['enclosure']['url'] =$this->prep( $itm['enclosure'], 'url', TRUE, FALSE );
			
			// Required if <enclosure> is used
			if ( is_array( $item['enclosure'] ))
				$item['enclosure']['length'] =$this->prep( $itm['enclosure'], 'length', TRUE, TRUE );
			
			// Required if <enclosure> is used
			if ( is_array( $item['enclosure'] ))
				$item['enclosure']['type'] =$this->prep( $itm['enclosure'], 'type', TRUE, FALSE );
			
			// Optional
			$item['pubDate'] =$this->prep( $itm, 'pubDate', TRUE, FALSE );
			
			// Optional
			$item['guid'] =( array_key_exists( 'guid', $itm ) && is_array( $itm['guid'] )) ? array() : NULL; 
			
			// Required if <guid> is used
			if ( is_array( $item['guid'] ))
				$item['guid']['content'] =$this->prep( $itm['guid'], 'content', TRUE, FALSE );
			
			// Optional (Default:true)
			if ( is_array( $item['guid'] ))
				$item['guid']['isPermaLink'] =$this->prep( $itm['guid'], 'isPermaLink', TRUE, FALSE );
			
			// Optional
			$item['source'] =( array_key_exists( 'source', $itm ) && is_array( $itm['source'] )) ? array() : NULL; 
			
			// Required if <source> is used
			if ( is_array( $item['source'] ))
				$item['source']['content'] =$this->prep( $itm['source'], 'content', TRUE, FALSE );
			
			// Required if <source> is used
			if (is_array( $item['source'] ))
				$item['source']['url'] =$this->prep( $itm['source'], 'url', TRUE, FALSE );
			
			/**
			 * Next, validate the item for required elements, sub-elements, and attributes
			 */
			
			if ( empty( $item['title'] ) && empty ( $item['description'] ))
				throw new Exception( "Either a title or a description is required for items." );
				
			if ( is_array( $item['category'] ))
				for ( $i =0 ; $i <count( $item['category'] ) ; $i++ )
					if ( empty( $item['category'][$i]['content'] ))
						throw new Exception( "The item 'category' tag must not be empty if it is used." );
				
			if ( is_array( $item['enclosure'] ) && empty ( $item['enclosure']['url'] ))
				throw new Exception( "The 'url' attribute of the 'enclosure' tag must not be empty." );
				
			if (is_array( $item['enclosure'] ) && empty ( $item['enclosure']['length'] ))
				throw new Exception( "The 'length' attribute of the 'enclosure' tag must not be empty." );
				
			if ( is_array( $item['enclosure'] ) && empty ( $item['enclosure']['type'] ))
				throw new Exception( "The 'type' attribute of the 'enclosure' tag must not be empty." );
				
			if ( is_array( $item['guid'] ) && empty ( $item['guid']['content'] ))
				throw new Exception( "The 'guid' tag must not be empty if it is used." );
				
			if ( is_array( $item['source'] ) && empty ( $item['source']['content'] ))
				throw new Exception( "The 'source' tag must not be empty if it is used." );
				
			if ( is_array( $item['source'] ) && empty ( $item['source']['url'] ))
				throw new Exception( "The 'url' attribute of the 'source' tag must not be empty." );
			
			/**
			 * If no errors were found, add the item to the $items array
			 */
					
			$this->items[] =&$item;
			
			return TRUE;
		}
		
		catch ( Exception $e ) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * The internal function which actually generates the XML content from the array collection 
	 *
	 * @access private
	 * @return boolean true on success, false on error
	 */
	private function generate()
	{
		try {
			
			/**
			 * Make sure there is at least one item...
			 */
			if ( count( $this->items ) < 1 ) 
				throw new Exception( "There must be at least one item." );
			
			/**
			 * Open the XML
			 */
			
			$this->rss  ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$this->rss .="<rss version=\"2.0\">\n";
			$this->rss .="  <channel>\n";
			
			/**
			 * Format the channel elements
			 */
			
			$this->rss .=sprintf( "    <title>%s</title>\n", $this->channel['title'] );
			
			$this->rss .=sprintf( "    <link>%s</link>\n", $this->channel['link'] );
			
			$this->rss .=sprintf( "    <description><!"."[CD"."ATA["."%s"."]"."]></description>\n", $this->channel['description'] );
			
			if ( $this->channel['language'] )
				$this->rss .=sprintf( "    <language>%s</language>\n", $this->channel['language'] );
			
			if ( $this->channel['copyright'] )
				$this->rss .=sprintf( "    <copyright>%s</copyright>\n", $this->channel['copyright'] );
			
			if ( $this->channel['managingEditor'] )
				$this->rss .=sprintf( "    <managingEditor>%s</managingEditor>\n", $this->channel['managingEditor'] );
			
			if ( $this->channel['webMaster'] )
				$this->rss .=sprintf( "    <webMaster>%s</webMaster>\n", $this->channel['webMaster'] );
			
			if ( $this->channel['pubDate'] )
				$this->rss .=sprintf( "    <pubDate>%s</pubDate>\n", $this->channel['pubDate'] );
			
			if ( $this->channel['lastBuildDate'] )
				$this->rss .=sprintf( "    <lastBuildDate>%s</lastBuildDate>\n", $this->channel['lastBuildDate'] );
			
			if ( is_array( $this->channel['category'] ))
			{
				for ($i =0 ; $i <count( $this->channel['category'] ) ; $i++)
				{
					$domain =( isset($this->channel['category'][$i]['domain']) && $this->channel['category'][$i]['domain'] ) ? sprintf( " domain=\"%s\"", $this->channel['category'][$i]['domain'] ) : NULL;
					
					$this->rss .=sprintf( "    <category%s>%s</category>\n", $domain, $this->channel['category'][$i]['content'] );
				}
			}
			
			if ( is_array( $this->channel['cloud'] ))
			{
				$domain            =sprintf( "domain=\"%s\"", $this->channel['cloud']['domain'] );
				$port              =sprintf( "port=\"%d\"", $this->channel['cloud']['port'] );
				$path              =sprintf( "path=\"%s\"", $this->channel['cloud']['path'] );
				$registerProcedure =sprintf( "registerProcedure=\"%s\"", $this->channel['cloud']['registerProcedure'] );
				$protocol          =sprintf( "protocol=\"%s\"", $this->channel['cloud']['protocol'] );
				
				$this->rss .=sprintf( "    <cloud %s %s %s %s %s />\n", $domain, $port, $path, $registerProcedure, $protocol );
			}
			
			if ( $this->channel['docs'] )
				$this->rss .=sprintf( "    <docs>%s</docs>\n", $this->channel['docs'] );
			
			if ( $this->channel['generator'] )
				$this->rss .=sprintf( "    <generator>%s</generator>\n", $this->channel['generator'] );
			
			if ( $this->channel['ttl'] )
				$this->rss .=sprintf( "    <ttl>%d</ttl>\n", $this->channel['ttl'] );
			
			if ( $this->channel['rating'] )
				$this->rss .=sprintf( "    <rating><!"."[CD"."ATA["."%s"."]"."]></rating>\n", $this->channel['rating'] );
			
			if ( is_array( $this->channel['textInput'] ))
			{
				$title       =sprintf( "      <title>%s</title>\n", $this->channel['textInput']['title'] );
				$description =sprintf( "      <description><!"."[CD"."ATA["."%s"."]"."]></description>\n", $this->channel['textInput']['description'] );
				$name        =sprintf( "      <name>%s</name>\n", $this->channel['textInput']['name'] );
				$link        =sprintf( "      <link>%s</link>\n", $this->channel['textInput']['link'] );
				
				$this->rss .=sprintf("    <textInput>\n%s%s%s%s    </textInput>\n", $title, $description, $name, $link);
			}
			
			if ( is_array( $this->channel['image'] ))
			{
				$url         =sprintf( "      <url>%s</url>\n", $this->channel['image']['url'] );
				$title       =sprintf( "      <title>%s</title>\n", $this->channel['image']['title'] );
				$link        =sprintf( "      <link>%s</link>\n", $this->channel['image']['link'] );
				$width       =( $this->channel['image']['width'] ) ? sprintf( "      <width>%d</width>\n", $this->channel['image']['width'] ) : NULL;
				$height      =( $this->channel['image']['height'] ) ? sprintf( "      <height>%d</height>\n", $this->channel['image']['height'] ) : NULL;
				$description =( $this->channel['image']['description'] ) ? sprintf( "      <description><!"."[CD"."ATA["."%s"."]"."]></description>\n", $this->channel['image']['description'] ) :NULL;
				
				$this->rss .=sprintf( "    <image>\n%s%s%s%s%s%s    </image>\n", $url, $title, $link, $width, $height, $description );
			}
			
			if ( $this->channel['skipHours'] )
			{
				$hours     =explode( ",", $this->channel['skipHours'] );
				$the_hours ="";
				
				foreach ( $hours as $hour )
					$the_hours .=sprintf( "      <hour>%d</hour>\n", $hour );
				
				$this->rss .=sprintf( "    <skipHours>\n%s    </skipHours>\n", $the_hours );
			}
			
			if ( $this->channel['skipDays'] )
			{
				$days     =explode( ",", $this->channel['skipDays'] );
				$the_days ="";
				
				foreach ( $days as $day )
					$the_days .=sprintf( "      <day>%s</day>\n", $day );
				
				$this->rss .=sprintf( "    <skipDays>\n%s    </skipDays>\n", $the_days );
			}
			
			/**
			 * Format the item elements
			 */
			
			foreach ( $this->items as $item )
			{
				$this->rss .="    <item>\n";
				
				if ( $item['title'] )
					$this->rss .=sprintf( "      <title>%s</title>\n", $item['title'] );
				
				if ( $item['link'] )
					$this->rss .=sprintf( "      <link>%s</link>\n", $item['link'] );
				
				if ( $item['description'] )
					$this->rss .=sprintf( "      <description><!"."[CD"."ATA["."%s"."]"."]></description>\n", $item['description'] );
				
				if ( $item['author'] )
					$this->rss .=sprintf( "      <author>%s</author>\n", $item['author'] );
				
				if ( is_array( $item['category'] ))
				{
					for ($i =0 ; $i <count( $item['category'] ) ; $i++)
					{
						$domain =( $item['category'][$i]['domain'] ) ? sprintf( " domain=\"%s\"", $item['category'][$i]['domain'] ) : NULL;
						
						$this->rss .=sprintf( "      <category%s>%s</category>\n", $domain, $item['category'][$i]['content'] );
					}
				}
				
				if ( $item['comments'] )
					$this->rss .=sprintf( "      <comments>%s</comments>\n", $item['comments'] );
				
				if ( $item['enclosure'] )
				{
					$url    =sprintf( "url=\"%s\"", $item['enclosure']['url'] );
					$length =sprintf( "length=\"%d\"", $item['enclosure']['length'] );
					$type   =sprintf( "type=\"%s\"", $item['enclosure']['type'] );
					
					$this->rss .=sprintf( "      <enclosure %s %s %s />\n", $url, $length, $type );
				}
				
				if ( $item['pubDate'] )
					$this->rss .=sprintf( "      <pubDate>%s</pubDate>\n", $item['pubDate'] );
				
				if ( $item['guid'] )
				{
					$isPermaLink =( $item['guid']['isPermaLink'] ) ? sprintf( " isPermaLink=\"%s\"", $item['guid']['isPermaLink']) : NULL;
					
					$this->rss .=sprintf( "      <guid%s>%s</guid>\n", $isPermaLink, $item['guid']['content'] );
				}
				
				if ( $item['source'] )
				{
					$url =sprintf( "url=\"%s\"", $item['source']['url'] );
					
					$this->rss .=sprintf( "      <source %s>%s</source>\n", $url, $item['source']['content'] );
				}
				
				$this->rss .="    </item>\n";
			}
				
			/**
			 * Close the XML
			 */
			$this->rss .="</channel>\n</rss>\n";
			
			return TRUE;
		}
		
		catch ( Exception $e ) {
			$this->errors[] =$e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * The function which executes the XML generation and export
	 *
	 * @param string $mode
	 * @param string $path_to_file
	 * @return void OR boolean true on success, false on error
	 */
	public function export( $mode ="browser", $path_to_file =NULL )
	{
		try {
			
			if ( !$this->generate() ) 
				throw new Exception( "Unable to generate the RSS content." );
		
			switch ( $mode )
			{
				case "download" :
				
					header( "Content-type: text/xml; charset=utf-8" );
					header( "Pragma: public" );
					header( "Cache-control: private" );
					header( "Expires: -1" );
					header( "Content-Disposition: attachment; filename=rss.xml" );
					header( "Content-Type: application/force-download" );
					
					exit ( $this->rss );
					
				case "write" :

                    //header( "Content-type: text/xml; charset=utf-8" );
					if ( empty( $path_to_file ))
						throw new Exception( "No file destination was given." );
					
					if ( !file_exists( $path_to_file ))
						if ( !touch( $path_to_file ))
							throw new Exception( "Unable to create the file '$path_to_file'." );
					
					if ( !$file =fopen( $path_to_file, "wb" ))
						throw new Error( "Unable to open file '$path_to_file' for writing." );
					
					if ( !fwrite( $file, $this->rss ))
						throw new Exception( "Unable to write contents to file '$path_to_file'." );
					
					if ( !fclose( $file ))
						throw new Exception( "Unable to close the file '$path_to_file'." );
					
					return TRUE;
					
				default :
					
					header( "Content-type: text/xml; charset=utf-8" );
					header( "Pragma: public" );
					header( "Cache-control: private" );
					header( "Expires: -1" );
					
					exit ( $this->rss );
			}
			
			return FALSE;
		}
		
		catch ( Exception $e ) {
			$this->errors[] =$e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * Helper function which translates special chars to HTML entities and strips tags if required
	 *
	 * @access private
	 * @param string $str
	 * @param boolean $strip_tags
	 * @return string
	 */
	private function clean( $str, $strip_tags =TRUE )
	{
		return ( $strip_tags ) ? trim( htmlspecialchars( strip_tags( $str ), ENT_NOQUOTES, 'UTF-8', FALSE )) : utf8_encode( trim( $str ));
	}
	
	/**
	 * Helper function which verifies the existence of values and attempts to clean them up
	 *
	 * @access private
	 * @param array $arr
	 * @param string $key
	 * @param boolean $strip_tags
	 * @param boolean $int
	 * @return mixed
	 */
	private function prep( &$arr, $key, $strip_tags =TRUE, $int =FALSE )
	{
		$val = ( array_key_exists( $key, $arr ) && !empty ( $arr[$key] )) ? $this->clean( $arr[$key], $strip_tags ) : NULL;
		
		if ( $val && $int )
			return intval( $val );
		
		else 
			return $val;
	}
}
?>