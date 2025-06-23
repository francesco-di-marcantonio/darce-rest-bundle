<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Tests\Unit\Service;

use App\Darce\RestBundle\Service\LocaleTranslatorManager;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class LocaleTranslatorManagerTest extends TestCase
{
    /**
     * Testa che in caso di lingua IT il locale venga impostato in it_IT
     */
    public function testItalianLocale(): void
    {
        LocaleTranslatorManager::setLocaleFromLanguage(LocaleTranslatorManager::DEFAULT_LANGUAGE);
        $date = new DateTime('1970-01-01');
        $jan = strftime('%B', $date->getTimestamp());

        $this->assertEquals('gennaio', $jan);
    }

    /**
     * Testa che in caso di lingua EN il locale venga impostato in en_GB
     */
    public function testEnglishLocale(): void
    {
        LocaleTranslatorManager::setLocaleFromLanguage(LocaleTranslatorManager::EN_LANGUAGE);
        $date = new DateTime('1970-01-01');
        $jan = strftime('%B', $date->getTimestamp());

        $this->assertEquals('January', $jan);
    }

    /**
     * Verifica che la lingua sia quella di default se la Request è null
     */
    public function testLanguageIfRequestIsNull(): void
    {
        $language = LocaleTranslatorManager::getLanguageFromRequest();
        $this->assertEquals(LocaleTranslatorManager::DEFAULT_LANGUAGE, $language);
    }

    public function testLanguageIfIt(): void
    {
        $request = new Request();
        $request->headers->set('Accept-Language', 'it');

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals(LocaleTranslatorManager::DEFAULT_LANGUAGE, $language);
    }

    public function testLanguageIfEn(): void
    {
        $request = new Request();
        $request->headers->set('Accept-Language', 'en');

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals(LocaleTranslatorManager::EN_LANGUAGE, $language);
    }

    public function testLanguageIfItWithCustomHeader(): void
    {
        $request = new Request();
        $request->headers->set('Language', 'it');

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals(LocaleTranslatorManager::DEFAULT_LANGUAGE, $language);
    }

    public function testLanguageIfEnWithCustomHeader(): void
    {
        $request = new Request();
        $request->headers->set('Language', 'en');

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals(LocaleTranslatorManager::EN_LANGUAGE, $language);
    }

    public function testLanguageWithCustomHeaderArray(): void
    {
        $request = new Request();
        $request->headers->set('Language', ['sp', 'it']);

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals('sp', $language);
    }

    public function testLanguageWithoutHeader(): void
    {
        $request = new Request();

        $language = LocaleTranslatorManager::getLanguageFromRequest($request);
        $this->assertEquals(LocaleTranslatorManager::DEFAULT_LANGUAGE, $language);
    }
}