<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\sprigtests\unit;

use Codeception\Test\Unit;
use Craft;
use putyourlightson\sprig\variables\SprigVariable;
use UnitTester;

/**
 * @author    PutYourLightsOn
 * @package   Sprig
 * @since     1.0.0
 */

class SprigVariableTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testHtmxScriptExistsLocally()
    {
        $sprigVariable = new SprigVariable();
        $client = Craft::createGuzzleClient();
        Craft::$app->getConfig()->env = 'dev';

        $script = $sprigVariable->getScript();
        preg_match('/src="(.*?)"/', (string)$script, $matches);
        $url = $matches[1];

        // Fix weird situation in which the URL becomes `craft3.`
        $url = str_replace('craft3.', 'craft3', $url);

        $this->assertEquals(200, $client->get($url)->getStatusCode());
    }

    public function testHtmxScriptExistsRemotely()
    {
        $sprigVariable = new SprigVariable();
        $client = Craft::createGuzzleClient();
        Craft::$app->getConfig()->env = 'production';

        $script = $sprigVariable->getScript();
        preg_match('/src="(.*?)"/', (string)$script, $matches);
        $url = $matches[1];
        $this->assertEquals(200, $client->get($url)->getStatusCode());
    }
}
