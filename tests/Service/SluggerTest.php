<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;

// je crée une classe de test pour chaque classe testée
// test unitaire => TestCase
class SluggerTest extends TestCase
{
    // je peux créer plusieur test en créant des methode dans ma classe de test
    // le nom des methodes de test doivent commencer par test...
    public function testSluggify()
    {
        // je fait ce qui doit être testé
        $slugger = new Slugger();
        $slug = $slugger->sluggify(" C'est Top ! ");

        // puis j'utilise le framework de test pour verifier que j'ai bien le resultat attendu
        // dans le cas présent la chaine à sluggifier est " C'est Top ! " mon slug doit correspondre à "c'est_top_!"
        $this->assertEquals("c'est_top_!", $slug);
    }
}