<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Service;

use Locale;
use Symfony\Component\HttpFoundation\Request;

/**
 * Non definito come servizio nel services.xml poiché contiene solo metodi statici
 */
final class LocaleTranslatorManager
{
    public const DEFAULT_LANGUAGE = 'it';
    public const DEFAULT_LOCALE = 'it_IT';

    public const EN_LANGUAGE = 'en';
    public const EN_LOCALE = 'en_GB';

    /**
     * Il locale di default è l'italiano
     *
     * @param Request|null $request
     * @return string
     */
    public static function getLanguageFromRequest(Request $request = null): string
    {
        $language = self::DEFAULT_LANGUAGE;

        if($request !== null && extension_loaded('intl')) {

            //Custom Header per alcuni progetti (Luiss Smart Campus in primis)
            $headerLanguage = $request->headers->get('Language');
            $headerAcceptLanguage = $request->headers->get('Accept-Language');

            if ($headerLanguage !== null){

                //Check se language è array
                if(is_array($headerLanguage) === true){
                    $language = self::transformIfArray($headerLanguage);
                }else{
                    $language = $headerLanguage;
                }

            }
            elseif ($headerAcceptLanguage !== null){

                if(is_array($headerAcceptLanguage) === true){
                    $language = self::transformIfArray($headerAcceptLanguage);
                }else{
                    $headerAcceptLanguage = Locale::acceptFromHttp($headerAcceptLanguage);
                    if(is_string($headerAcceptLanguage)){
                        $language = $headerAcceptLanguage;
                    }
                }
            }

            if($language === '') {
                $language = self::DEFAULT_LANGUAGE;
            }

            //Validazione del locale. La regex è quella di \Symfony\Component\Translation\Translator::assertValidLocale
            if (1 !== preg_match('/^[a-z0-9@_\\.\\-]*$/i', $language)) {
                $language = self::DEFAULT_LANGUAGE;
            }
        }

        return $language;
    }

    /**
     * @param string $language
     */
    public static function setLocaleFromLanguage(string $language): void
    {
        if($language === self::DEFAULT_LANGUAGE){
            setlocale(LC_ALL, self::DEFAULT_LOCALE);
        }elseif ($language === self::EN_LANGUAGE){
            setlocale(LC_ALL, self::EN_LOCALE);
        }
    }

    /**
     * @param array $languages
     * @return string
     */
    private static function transformIfArray(array $languages): string
    {
        if(count($languages) > 0){
            return $languages[0];
        }

        return self::DEFAULT_LANGUAGE;
    }
}