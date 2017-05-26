<?php
/**
 * 2017 mpSOFT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    mpSOFT <info@mpsoft.it>
 *  @copyright 2017 mpSOFT Massimiliano Palermo
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of mpSOFT
 */

class ClassBrtMerce
{
    private $colli;
    private $natura_merce;
    private $peso_kg;
    private $volume_m3;
    
    public function __construct($merce)
    {
        $this->colli = $merce->COLLI;
        $this->natura_merce = $merce->NATURA_MERCE;
        $this->peso_kg = $merce->PESO_KG;
        $this->volume_m3 = $merce->VOLUME_M3;
    }
    
    public function getColli()
    {
        return $this->colli;
    }

    public function getNaturaMerce()
    {
        return $this->natura_merce;
    }

    public function getPesoKg()
    {
        return $this->peso_kg;
    }

    public function getVolumeM3()
    {
        return $this->volume_m3;
    }
}
