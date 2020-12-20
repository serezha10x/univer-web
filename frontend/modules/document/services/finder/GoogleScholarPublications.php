<?php


namespace frontend\modules\document\services\finder;


use frontend\modules\document\models\Document;
use frontend\modules\document\services\DocumentConvertable;
use Goutte\Client;
use GScholarProfileParser\DomCrawler\ProfilePageCrawler;
use GScholarProfileParser\Entity\Publication;
use GScholarProfileParser\Parser\PublicationParser;

class GoogleScholarPublications implements DocumentConvertable
{
    private $teacherId;

    /**
     * GoogleScholarPublications constructor.
     * @param $teacherId
     */
    public function __construct(string $teacherId)
    {
        $this->teacherId = $teacherId;
    }


    public function getPublications()
    {
        /** @var Client $client */
        $client = new Client();

        /** @var ProfilePageCrawler $crawler */
        $crawler = new ProfilePageCrawler($client, $this->teacherId); // the second parameter is the scholar's profile id

        /** @var PublicationParser $parser */
        $parser = new PublicationParser($crawler->getCrawler());

        /** @var array<int, array<string, string>> $publications */
        $publications = $parser->parse();
        // hydrates items of $publications into Publication

        $publicationsObj = [];
        foreach ($publications as $publication) {
            /** @var Publication $publication */
            $publication = new Publication($publication);
            $publicationsObj[] = $publication;
        }
        return $publicationsObj;
//        // displays latest publication data
//        echo $latestPublication->getTitle(), "\n";
//        echo $latestPublication->getPublicationURL(), "\n";
//        echo $latestPublication->getAuthors(), "\n";
//        echo $latestPublication->getPublisherDetails(), "\n";
//        echo $latestPublication->getNbCitations(), "\n";
//        echo $latestPublication->getCitationsURL(), "\n";
//        echo $latestPublication->getYear(), "\n";
    }

    public function convertToDocuments(array $from): array
    {
        $docs = [];
        foreach ($from as $publication) {
            $doc = new Document();
            $doc->document_name = $publication->getPublicationURL();
            $doc->year = $publication->getYear();
            $doc->description = $publication->getPublisherDetails();
            $docs[] = $doc;
        }

        return $docs;
    }
}