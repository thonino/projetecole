<?php

namespace App\Test\Controller;

use App\Entity\Etudiants;
use App\Repository\EtudiantsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EtudiantsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EtudiantsRepository $repository;
    private string $path = '/etudiants/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Etudiants::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'etudiant[nom]' => 'Testing',
            'etudiant[prenom]' => 'Testing',
            'etudiant[age]' => 'Testing',
            'etudiant[adresse]' => 'Testing',
            'etudiant[telephone]' => 'Testing',
            'etudiant[email]' => 'Testing',
            'etudiant[document]' => 'Testing',
        ]);

        self::assertResponseRedirects('/etudiants/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiants();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAge('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setDocument('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiants();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAge('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setDocument('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etudiant[nom]' => 'Something New',
            'etudiant[prenom]' => 'Something New',
            'etudiant[age]' => 'Something New',
            'etudiant[adresse]' => 'Something New',
            'etudiant[telephone]' => 'Something New',
            'etudiant[email]' => 'Something New',
            'etudiant[document]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etudiants/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getAge());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getDocument());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Etudiants();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAge('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setDocument('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/etudiants/');
    }
}
