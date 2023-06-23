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

            $link = $dom->createElement("link", "http://localhost/TWProject/RSS/feed.xml");
            $channel->appendChild($link);

            $description = $dom->createElement("description", "Top 10 Users with the most points");
            $channel->appendChild($description);

            $language = $dom->createElement("language", "en-us");
            $channel->appendChild($language);

            // publish date -> element must be an RFC-822 date-time
            $pubDate = $dom->createElement("pubDate", date("D, d M Y H:i:s O"));
            $channel->appendChild($pubDate);

            // author
            $managingEditor = $dom->createElement("managingEditor", "fruitsonthewebcontact@gmail.com");
            $channel->appendChild($managingEditor);

            // generator
            $generator = $dom->createElement("generator", "Fruits on the Web");
            $channel->appendChild($generator);

            // add items to channel -> top 10 users
            $userDAO = new UserDAO();

            $users = $userDAO->findFirstTenByRanking();

            foreach ($users as $user) {
                $item = $dom->createElement("item");
                //                <guid>unique-identifier</guid>
                $guid = $dom->createElement("guid", $user['ranking']);
                $item->appendChild($guid);

                $channel->appendChild($item);

                $title = $dom->createElement("title", $user['username']);
                $item->appendChild($title);

                $position = $user['ranking'];
                $username = $user['username'];
                $points = $user['points'];
                $playingSince = $user['created_at'];



                $contentEncoded = $dom->createElement("content");
                $contentEncoded->setAttribute("type", "html");
                $content = $dom->createTextNode(
                    "<p>Position: $position</p>
                    <p>Username: $username</p>
                    <p>Points: $points</p>
                    <p>Playing since: $playingSince</p>"
                );

                $contentEncoded->appendChild($content);

                $item->appendChild($contentEncoded);
            }

            $dom->save("../RSS/feed.xml");
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}