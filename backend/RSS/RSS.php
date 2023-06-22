<?php

class RSS {

    // static function updateRSS() that updates the RSS feed located in feed.xml
    // the RSS feed should contain the top 10 users with the most points
    // the RSS feed should be updated when: a user is added, a user is deleted, a user's points are updated

    static function updateRSS(){
        try {

            header("Content-Type: application/rss+xml; charset=utf-8");

            $dom = new DOMDocument("1.0", "UTF-8");

            $rss = $dom->createElement("rss");
            $rss->setAttribute("version", "2.0");

            $dom->appendChild($rss);

            $channel = $dom->createElement("channel");
            $rss->appendChild($channel);

            $title = $dom->createElement("title", "Top 10 Users");
            $channel->appendChild($title);

            $link = $dom->createElement("link", "/RSS/feed.xml");
            $channel->appendChild($link);

            $description = $dom->createElement("description", "Top 10 Users with the most points");
            $channel->appendChild($description);

            $language = $dom->createElement("language", "en-us");
            $channel->appendChild($language);

            // publish date
            $pubDate = $dom->createElement("pubDate", date("Y-m-d\TH:i:s\Z"));
            $channel->appendChild($pubDate);

            // author
            $author = $dom->createElement("author", "Fruits on the Web");
            $channel->appendChild($author);

            // generator
            $generator = $dom->createElement("generator", "Fruits on the Web");
            $channel->appendChild($generator);

            // add items to channel -> top 10 users
            $userDAO = new UserDAO();

            $users = $userDAO->findFirstTenByRanking();

            foreach ($users as $user) {
                $item = $dom->createElement("item");
                $channel->appendChild($item);

                $position = $dom->createElement("position", $user['ranking']);
                $item->appendChild($position);

                $username = $dom->createElement("username", $user['username']);
                $item->appendChild($username);

                $points = $dom->createElement("points", $user['points']);
                $item->appendChild($points);

                $playingSince = $dom->createElement("playingSince", $user['created_at']);
                $item->appendChild($playingSince);
            }

            $dom->save("../RSS/feed.xml");
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}