<?php

namespace Tmv\WhatsApi\Entity;

use Tmv\WhatsApi\Service\LocalizationService;

class PhoneFactory
{
    /**
     * @var LocalizationService
     */
    protected $localizationService;

    /**
     * @param LocalizationService $localizationService
     */
    public function __construct(LocalizationService $localizationService = null)
    {
        if ($localizationService) {
            $this->setLocalizationService($localizationService);
        }
    }


    /**
     * @return LocalizationService
     */
    public function getLocalizationService()
    {
        if (!$this->localizationService) {
            $this->localizationService = new LocalizationService();
        }
        return $this->localizationService;
    }

    /**
     * @param LocalizationService $localizationService
     * @return $this
     */
    public function setLocalizationService(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
        return $this;
    }

    /**
     * @param string $number
     * @return Phone
     */
    public function createPhone($number)
    {
        $phone = new Phone($number);
        $this->getLocalizationService()->injectPhoneProperties($phone);
        return $phone;
    }
}
