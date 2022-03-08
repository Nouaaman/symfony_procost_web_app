<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture 
{

    private const DATA_JOBS = [
        ['Web designer'],
        ['SEO Manager'],
        ['Web Developer'],
        ['Software Developer'],
        ['Network Developer'],
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadJobs();

        $manager->flush();
    }


    private function loadJobs(): void
    {
        foreach (self::DATA_JOBS as $key => [$name]) {
            $job = (new Job())
                ->setName($name);

            $this->manager->persist($job);
            // $this->addReference(Job::class . $key, $job);

        }
    }
}
