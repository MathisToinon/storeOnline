<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Line;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setContent('content : ' . $i);
            $article->setPrice($i);
            if($i%2 == 0){
                $article->setAvailable(false);
                $line = new Line();
                $line->setQuantity($i);
                $line->setArticles($article);
                $manager->persist($article);
                $manager->persist($line);
            } else {
                $article->setAvailable(true);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
