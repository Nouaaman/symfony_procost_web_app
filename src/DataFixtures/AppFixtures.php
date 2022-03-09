<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Job;
use App\Entity\Project;
use DateTime;
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
        $this->loadEmployees();
        $this->loadProjects();
        $manager->flush();
    }


    private function loadJobs(): void
    {
        foreach (self::DATA_JOBS as $key => [$name]) {
            $job = (new Job())
                ->setName($name);

            $this->manager->persist($job);
            $this->addReference(Job::class . $key, $job);
        }
    }

    private function loadEmployees(): void
    {
        for ($i = 1; $i < 15; $i++) {
            $employee = (new Employee())
                ->setFirstName('FirstName ' . $i)
                ->setLastName('LastName ' . $i)
                ->setEmail('email' . $i . '@email.com')
                ->setDailyCost(1800, 0)
                ->setHiringDate(new DateTime())
                ->setIdJob($this->getReference(Job::class . random_int(0, count(self::DATA_JOBS) - 1)));

            $this->manager->persist($employee);
        }
    }

    private function loadProjects(): void
    {
        for ($i = 1; $i < 10; $i++) {
            $project = (new Project())
                ->setName('Project ' . $i)
                ->setDescription('this is a description ' . $i . '.')
                ->setCreationDate(new DateTime());
            $this->manager->persist($project);
        }
    }
}
