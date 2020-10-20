<?php

require 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

header('Content-Type: application/json');

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    //key api scraperapi
    $key = '';
    // Set up your cURL handle(s).
    $ch = curl_init();
    $url = "http://api.scraperapi.com/account?api_key=" . $key;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    //Concurrent variable with Subscription Plans
    $concurrent_limit = json_decode($response, TRUE)['concurrentRequests'];
    if ($concurrent_limit > 4) {
        http_response_code(401);
        echo 'Concurrent limit exceeded, please wait and try again.';
        exit;
    }
    $filter_keyword = str_replace(' ', '+', $_GET['keyword']);
    // Set up your cURL handle(s).
    $ch = curl_init();
    $url = "https://www.google.com/search?num=100&pws=0&client=ms-google-coop&q=$filter_keyword&oq=$filter_keyword&gl=fr&hl=fr";
    curl_setopt($ch, CURLOPT_URL,
        "http://api.scraperapi.com/?api_key=$key&url=$url&keep_headers=true");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/78.0.3904.108 Chrome/78.0.3904.108 Safari/537.36"
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);

    $crawler = new Crawler($response);
    // infos
    $countRes = $crawler->filter('#result-stats')->count();
    if ($countRes === 0) {
        http_response_code(401);
        echo json_encode(['codeResponse' => '401', 'message' => 'Error : Try again']);
        exit;
        die();
    }

    $nbResult = preg_replace("/[^0-9]/", "", $crawler->filter('#result-stats')->text());
    // organic search
    $urls = $crawler->filter('.rc > .yuRUbf > a')->each(function (Crawler $node) {
        $link = $node->link();
        return $link->getUri();
    });
    $description = $crawler->filter('.rc > .IsZvec > div > .aCOpRe > span')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $title = $crawler->filter('.rc > .yuRUbf > a > h3')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $link = $crawler->filter('.rc > .yuRUbf > .B6fmyf > .TbwUpd > cite')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $cached = $crawler->filter('.rc > .yuRUbf > .B6fmyf > .eFM0qc > span > .action-menu > ol > li > a[ping]')->each(function (Crawler $node) {
        $link = $node->link();
        return $link->getUri();
    });
    $cached = $crawler->filter('.rc > .yuRUbf > .B6fmyf > .eFM0qc > span > .action-menu > ol > li > a[ping]')->each(function (Crawler $node) {
        $link = $node->link();
        return $link->getUri();
    });

    //Question
    $questionTitle = $crawler->filter('.kno-kp > .kp-blk > .xpdopen > .ifM9O > .feCgPc .mWyH1d')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });

    // knowledge_graph
    $kTitle = $crawler->filter('.kp-wholepage .SPZz6b > h2[data-attrid="title"]')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $kResume = $crawler->filter('.kp-wholepage .kno-rdesc span')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $klink = $crawler->filter('.kp-wholepage .kno-rdesc a')->each(function (Crawler $node) {
        $link = $node->link();
        return $link->getUri();
    });
    $kname = $crawler->filter('.kp-wholepage .kno-rdesc a')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $ksearchlinked = $crawler->filter('.liYKde .kp-wholepage .osrp-blk .CGCvRb .MRfBrb a')->each(function (Crawler $node) {
        $link = $node->attr('href');
        return str_replace('http://api.scraperapi.com', '', $link);
    });
    $ksearchlinkedTitle = $crawler->filter('.kp-wholepage .CGCvRb .zVvuGd .ellip')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $ksearchlinkedImg = $crawler->filter('.kp-wholepage .CGCvRb .zVvuGd g-img > img')->each(function (Crawler $node) {
        $text = $node->attr('src');
        return $text;
    });
    //local search
    $linkLocal = $crawler->filter('div[data-hveid="CGcQAA"] .xERobd .ccBEnf a.C8TUKc')->each(function (Crawler $node) {
        $link = $node->link();
        return str_replace('http://api.scraperapi.com', '', $link->getUri());
    });
    $titleLocal = $crawler->filter('.xERobd .ccBEnf .dbg0pd')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $ratingLocal = $crawler->filter('.xERobd .ccBEnf .BTtC6e')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $reviewsLocal = $crawler->filter('.xERobd .ccBEnf .rllt__details > div > span[aria-label]')->each(function (Crawler $node) {
        $text = $node->text();
        return str_replace(array('(', ')'), '', $text);
    });
    //News Block
    $BlockHeaderLink = $crawler->filter('g-section-with-header > div[data-hveid="CA4QAQ"] > div > .qmv19b div a')->each(function (Crawler $node) {
        $link = $node->link();
        return $link->getUri();
    });
    $BlockHeaderLinkMore = $crawler->filter('g-section-with-header > div[data-hveid="CA4QAQ"] > div > .qmv19b > g-more-link > a')->each(function (Crawler $node) {
        $link = $node->link();
        return str_replace('http://api.scraperapi.com', '', $link->getUri());
    });
    $BlockHeaderTitle = $crawler->filter('g-section-with-header > div[data-hveid="CA4QAQ"] > div > .qmv19b .Od9uAe > .y9oXvf > .nDgy9d')->each(function (Crawler $node) {
        $text = $node->text();
        return $text;
    });
    $BlockHeaderImg = $crawler->filter('g-section-with-header > div[data-hveid="CA4QAQ"] > div > .qmv19b .KNcnob g-img > img')->each(function (Crawler $node) {
        $text = $node->attr('src');
        return $text;
    });

    function myFilter($string)
    {
        $element = 'https://translate.google.com/translate';
        return strpos($string, $element) === false;
    }

    $urls = array_values(array_filter($urls, 'myFilter'));

    $json_google['infos'][] = [
        'query_filter' => $_GET['keyword'],
        'nb_results' => $nbResult
    ];
    foreach ($linkLocal as $kLocal => $local) {
        $json_google['localResult'][] = [
            'link' => $local,
            'title' => $titleLocal[$kLocal],
            'rating' => $ratingLocal[$kLocal],
            'reviews' => $reviewsLocal[$kLocal]
        ];
    }
    foreach ($BlockHeaderLink as $kBlockHeader => $vBlockHeader) {
        $json_google['HeaderBlock'][] = [
            'link' => $vBlockHeader,
            'title' => $BlockHeaderTitle[$kBlockHeader],
            'img_src' => $BlockHeaderImg[$kBlockHeader]
        ];
    }

    $json_google['HeaderBlock'][] = [
        'linkMore' => current($BlockHeaderLinkMore)
    ];

    foreach ($questionTitle as $kQ => $vQ) {
        $json_google['questionList'][] = [
            'title' => $vQ
        ];
    }

    $json_google['knowledge_graph'] = [
        'title' => current($kTitle),
        'description' => current($kResume),
        'source' => [
            'link' => current($klink),
            'name' => current($kname)
        ]
    ];
    foreach ($ksearchlinked as $kSearch => $kVSearch) {
        $json_google['knowledge_graph']['associatedResearch'][] = [

            'link' => $kVSearch,
            'title' => $ksearchlinkedTitle[$kSearch],
            'img' => $ksearchlinkedImg[$kSearch]

        ];
    }

    foreach ($urls as $key => $url) {
        $rank = $key + 1;
        $domain = parse_url($url, PHP_URL_HOST);
        $cacheFind = [];
        foreach ($cached as $kCache => $vCache) {
            if (strpos($vCache, $url)) {
                $cacheFind[$key] = $vCache;
            }
        }

        $json_google['organic'][] = [
            'position' => $rank,
            'url' => $url,
            'title' => $title[$key],
            'description' => $description[$key],
            'link' => $link[$key],
            'cachedLink' => isset($cacheFind[$key]) ? $cacheFind[$key] : ''
        ];

    }
    echo json_encode($json_google);
    exit();
}
http_response_code(400);
echo json_encode(['codeResponse' => '400']);
exit;