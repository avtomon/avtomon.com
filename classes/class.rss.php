<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 25.05.15
 * Time: 12:38
 */

class RSSException extends Exception { }

require_once('ABC_RSS.php');

class RSS
{
    private $path = false;
    private $rss = false;
    private $channel = false;
    private $items = false;
    private $tags = false;

    function __construct ($type = false)
    {
        $date = date(DATE_RSS);

        $this->rss = new ABC_RSS();

        $this->items = new Post();
        if (!$this->items)
        {
            throw new RSSException('Не удалось создать объект постов');
        }

        $this->tags = new Tag();
        if (!$this->tags)
        {
            throw new RSSException('Не удалось создать объект тегов');
        }

        $this->channel = ['link' => RSS_DOMAIN,
                          'language' => RSS_LANG,
                          'copyright' => RSS_COPYRIGHT ,
                          'managingEdition' => RSS_MANAGING_EDITION,
                          'webMaster' => RSS_MANAGING_EDITION,
                          'pubDate' => $date,
                          'lastBuildDate' => $date,
                          'ttl' => RSS_TTL,
                          'image' =>
                          [
                              'url' => RSS_IMAGE_URL,
                              'title' => RSS_IMAGE_TITLE,
                              'link' => RSS_IMAGE_LINK,
                              'width' => RSS_IMAGE_WIDTH,
                              'height' => RSS_IMAGE_HEIGHT
                          ]];

        $this->switchType($type);
        $this->rss->set_channel($this->channel);
    }

    public function switchType ($type)
    {
        if ($type)
        {
            switch ($type)
            {
                case 'Новость':
                    $href = RSS_DOMAIN . '/news/tag/';
                    $this->path = 'news_rss.xml';
                    $this->channel['title'] = 'Все новости ' . RSS_DOMAIN;
                    $this->channel['description'] = 'Все новости блога ' . RSS_DOMAIN;
                    break;

                case 'Статья':
                    $href = RSS_DOMAIN . '/articles/tag/';
                    $this->path = 'articles_rss.xml';
                    $this->channel['title'] = 'Все статьи ' . RSS_DOMAIN;
                    $this->channel['description'] = 'Все статьи блога ' . RSS_DOMAIN;
                    break;

                case 'Мнение':
                    $href = RSS_DOMAIN . '/views/tag/';
                    $this->path = 'views_rss.xml';
                    $this->channel['title'] = 'Все мнения ' . RSS_DOMAIN;
                    $this->channel['description'] = 'Все мнения технологического блога ' . RSS_DOMAIN;
                    break;

                default:
                    throw new RSSException('Передан неверный тип постов');
            }
            $this->channel['category'] = $this->getTagsFromTypeToRSS($type, $href);
        }
        else
        {
            $this->path = 'rss.xml';
            $this->channel['title'] = 'Все публикации ' . RSS_DOMAIN;
            $this->channel['description'] = 'Все публикации блога ' . RSS_DOMAIN;
            $this->channel['category'] = $this->getAllTags();
        }
    }

    private function getAllTags ()
    {
        $tags = $this->tags->getPostsTags();
        $category = [];
        if (is_array($tags))
        {
            foreach ($tags as & $value)
            {
                $category[] = ['content' => $value['tag']];
            }
            unset($value);
        }

        return $category;
    }

    private function getTagsFromTypeToRSS ($type, $href)
    {
        $category = [];
        $tags = $this->tags->getTagsFromType(['type' => $type]);
        if (is_array($tags))
        {
            foreach ($tags as & $value)
            {
                $category[] = ['content' => $value['tag'], ' domain' => $href . $value['id']];
            }
            unset($value);
        }

        return $category;
    }

    private function buildItems (array $items, $type = false)
    {
        foreach ($items as $key => & $value)
        {
            $value['comments'] = $value['link'];
            $value['guid'] = ['content' => $value['link'], 'isPermaLink' => true];
            $value['pubDate'] = date(DATE_RSS, $value['pubdate']);
            $value['category'] = $value['category'] != '[null]' ? json_decode($value['category'], true) : false;
            if (is_array($value['category']))
            {
                foreach ($value['category'] as $key2 => & $value2)
                {
                    $value2['content'] = $value2['tag'];
                    if ($type)
                    {
                        $value2['domain'] = RSS_DOMAIN . '/' . $type . '/tag' . $value2['id'];
                    }
                }
                unset($value2);
            }
            $value['source'] = ['content' => $value['source_title'],
                                'url' => $this->path];

            unset($value['source_title'], $value['pubdate']);

            $this->rss->set_item($value);
        }
        unset($value);

        return true;
    }

    public function createAllRSS ()
    {
        $items = $this->items->getRSSPosts();
        if (is_array($items))
        {
            if ($this->buildItems($items))
            {
                $this->rss->export('write', $this->path);
            }
        }
    }

    private function createRSSFromType ($type)
    {
        $items = $this->items->{'getRSS' . ucfirst($type)}();
        if (is_array($items))
        {
            if ($this->buildItems($items, $type))
            {
                $this->rss->export('write', $this->path);
            }
        }
    }

    public function createRSSFromLiteralType ($type)
    {
        switch ($type)
        {
            case 'Новость':
                $type = 'news';
                break;

            case 'Статья':
                $type = 'articles';
                break;

            case 'Мнение':
                $type = 'views';
                break;

            default:
                throw new RSSException('Передан неверный тип постов');
        }
        $this->createRSSFromType($type);
    }

    public function createNewsRSS ()
    {
        $this->createRSSFromType('news');
    }

    public function createArticlesRSS ()
    {
        $this->createRSSFromType('articles');
    }

    public function createViewsRSS ()
    {
        $this->createRSSFromType('views');
    }

}