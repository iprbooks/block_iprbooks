<?php

use Iprbooks\Ebs\Sdk\Client;
use Iprbooks\Ebs\Sdk\collections\BooksCollection;
use Iprbooks\Ebs\Sdk\collections\JournalsCollection;
use Iprbooks\Ebs\Sdk\Managers\IntegrationManager;
use Iprbooks\Ebs\Sdk\Models\User;

define('AJAX_SCRIPT', true);
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/iprbooks/vendor/autoload.php');

require_login();
$action = optional_param('action', "", PARAM_TEXT);
$type = optional_param('type', "", PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);

//book filter
$filter_book = array(
    'ipr-filter-book-title' => optional_param('ipr-filter-book-title', "", PARAM_TEXT),
    'ipr-filter-book-pubhouse' => optional_param('ipr-filter-book-pubhouse', "", PARAM_TEXT),
    'ipr-filter-book-author' => optional_param('ipr-filter-book-author', "", PARAM_TEXT),
    'ipr-filter-book-yearleft' => optional_param('ipr-filter-book-yearleft', "", PARAM_TEXT),
    'ipr-filter-book-yearright' => optional_param('ipr-filter-book-yearright', "", PARAM_TEXT)
);

//journal filter
$filter_journal = array(
    'ipr-filter-journal-title' => optional_param('ipr-filter-journal-title', "", PARAM_TEXT),
    'ipr-filter-journal-pubhouse' => optional_param('ipr-filter-journal-pubhouse', "", PARAM_TEXT),
);


$clientId = get_config('iprbooks', 'user_id');
$token = get_config('iprbooks', 'user_token');

//$clientId = 187;
//$token = '5G[Usd=6]~F!b+L<a4I)Ya9S}Pb{McGX';

$content = "";
try {
    $client = new Client($clientId, $token);
} catch (Exception $e) {
    die();
}

$integrationManager = new IntegrationManager($client);

switch ($action) {
    case 'getlist':
        switch ($type) {
            case 'book':
                $booksCollection = new BooksCollection($client);

                //set filters
                $booksCollection->setFilter(BooksCollection::TITLE, $filter_book['ipr-filter-book-title']);
                $booksCollection->setFilter(BooksCollection::PUBHOUSE, $filter_book['ipr-filter-book-pubhouse']);
                $booksCollection->setFilter(BooksCollection::AUTHOR, $filter_book['ipr-filter-book-author']);
                $booksCollection->setFilter(BooksCollection::YEAR_LEFT, $filter_book['ipr-filter-book-yearleft']);
                $booksCollection->setFilter(BooksCollection::YEAR_RIGHT, $filter_book['ipr-filter-book-yearright']);

                $booksCollection->setOffset($booksCollection->getLimit() * $page);
                $booksCollection->get();

                foreach ($booksCollection as $book) {
                    $autoLoginUrl = $integrationManager->generateAutoAuthUrl($USER->email, "", User::STUDENT, $book->getId());

                    $content .= "<div class=\"ipr-item\" data-id=\"" . $book->getId() . "\">
                                    <div class=\"row\" style='padding: 10px 0'>
                                        <div id=\"ipr-item-image-" . $book->getId() . "\" class=\"col-sm-3\">
                                            <img src=\"" . $book->getImage() . "\" class=\"img-responsive thumbnail\" alt=\"\">
                                            <a id=\"ipr-item-url-" . $book->getId() . "\" href=\"" . $autoLoginUrl . "\"></a>
                                        </div>
                                        <div class=\"col-sm-8\">
                                            <div id=\"ipr-item-title-" . $book->getId() . "\"><strong>Название:</strong> " . $book->getTitle() . " </div>
                                            <div id=\"ipr-item-title_additional-" . $book->getId() . "\" hidden><strong>Альтернативное
                                                название:</strong> " . $book->getTitleAdditional() . " </div>
                                            <div id=\"ipr-item-pubhouse-" . $book->getId() . "\"><strong>Издательство:</strong> " . $book->getPubhouse() . " </div>
                                            <div id=\"ipr-item-authors-" . $book->getId() . "\"><strong>Авторы:</strong> " . $book->getAuthors() . " </div>
                                            <div id=\"ipr-item-pubyear-" . $book->getId() . "\"><strong>Год издания:</strong> " . $book->getPubyear() . " </div>
                                            <div id=\"ipr-item-description-" . $book->getId() . "\" hidden><strong>Описание:</strong> " . $book->getDescription() . " </div>
                                            <div id=\"ipr-item-keywords-" . $book->getId() . "\" hidden><strong>Ключевые слова:</strong> " . $book->getKeywords() . " </div>
                                            <div id=\"ipr-item-pubtype-" . $book->getId() . "\" hidden><strong>Тип издания:</strong> " . $book->getPubtype() . " </div>
                                        </div>
                                    </div>
                                </div>";
                }

                $content .= pagination($booksCollection->getTotalCount(), $page + 1);
                break;

            case 'journal':
                $journalsCollection = new JournalsCollection($client);

                //set filters
                $journalsCollection->setFilter(JournalsCollection::TITLE, $filter_journal['ipr-filter-journal-title']);
                $journalsCollection->setFilter(JournalsCollection::PUBHOUSE, $filter_journal['ipr-filter-journal-pubhouse']);

                $journalsCollection->setOffset($journalsCollection->getLimit() * $page);
                $journalsCollection->get();

                foreach ($journalsCollection as $journal) {
                    $autoLoginUrl = $integrationManager->generateAutoAuthUrl($USER->email, "", User::STUDENT, $journal->getId());
                    $content .= "<div class=\"ipr-item\" data-id=\"" . $journal->getId() . "\">
                                    <div class=\"row\" style='padding: 10px 0'>
                                        <div id=\"ipr-item-image-" . $journal->getId() . "\" class=\"col-sm-3\">
                                            <img src=\"" . $journal->getImage() . "\" class=\"img-responsive thumbnail\" alt=\"\">
                                            <a id=\"ipr-item-url-" . $journal->getId() . "\" href=\"" . $autoLoginUrl . "\"></a>
                                        </div>
                                        <div class=\"col-sm-8\">
                                            <div id=\"ipr-item-title-" . $journal->getId() . "\"><strong>Название:</strong> " . $journal->getTitle() . "</div>
                                            <div id=\"ipr-item-title_additional-" . $journal->getId() . "\" hidden></div>
                                            <div id=\"ipr-item-pubhouse-" . $journal->getId() . "\"><strong>Издательство:</strong> " . $journal->getPubhouse() . "</div>
                                            <div id=\"ipr-item-authors-" . $journal->getId() . "\"></div>
                                            <div id=\"ipr-item-pubyear-" . $journal->getId() . "\"></div>
                                            <div id=\"ipr-item-description-" . $journal->getId() . "\" hidden><strong>Описание:</strong> " . $journal->getDescription() . "</div>
                                            <div id=\"ipr-item-keywords-" . $journal->getId() . "\" hidden><strong>Ключевые слова:</strong> " . $journal->getKeywords() . "</div>
                                            <div id=\"ipr-item-pubtype-" . $journal->getId() . "\" hidden></div>
                                        </div>
                                    </div>
                                </div>";
                }

                $content .= pagination($journalsCollection->getTotalCount(), $page + 1);
                break;

            case 'user':
                break;
        }
        break;
}

if (mb_strlen($content) < 200) {
    $content = '<div style="font-size: 150%; text-align: center;">По вашему запросу ничего не найдено</div>' . $content;
}

echo json_encode(['action' => $action, 'type' => $type, 'page' => $page, 'html' => $content]);

function pagination($count, $page)
{
    $output = '';
    $output .= "<nav aria-label=\"Страница\" class=\"pagination pagination-centered justify-content-center\"><ul class=\"mt-1 pagination \">";
    $pages = ceil($count / 10);


    if ($pages > 1) {

        if ($page > 1) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . ($page - 2) . "\" class=\"page-link ipr-page\" ><span>«</span></a></li>";
        }
        if (($page - 3) > 0) {
            $output .= "<li class=\"page-item \"><a data-page=\"0\" class=\"page-link ipr-page\">1</a></li>";
        }
        if (($page - 3) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link ipr-page\">...</span></li>";
        }


        for ($i = ($page - 2); $i <= ($page + 2); $i++) {
            if ($i < 1) continue;
            if ($i > $pages) break;
            if ($page == $i)
                $output .= "<li class=\"page-item active\"><a data-page=\"" . ($i - 1) . "\" class=\"page-link ipr-page\" >" . $i . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($i - 1) . "\" class=\"page-link ipr-page\">" . $i . "</a></li>";
        }


        if (($pages - ($page + 2)) > 1) {
            $output .= "<li class=\"page-item disabled\"><span class=\"page-link ipr-page\">...</span></li>";
        }
        if (($pages - ($page + 2)) > 0) {
            if ($page == $pages)
                $output .= "<li class=\"page-item active\"><a data-page=\"" . ($pages - 1) . "\" class=\"page-link ipr-page\" >" . $pages . "</a ></li > ";
            else
                $output .= "<li class=\"page-item \"><a data-page=\"" . ($pages - 1) . "\" class=\"page-link ipr-page\">" . $pages . "</a></li>";
        }
        if ($page < $pages) {
            $output .= "<li class=\"page-item\"><a data-page=\"" . $page . "\" class=\"page-link ipr-page\"><span>»</span></a></li>";
        }

    }

    $output .= "</ul></nav>";
    return $output;
}


die();
