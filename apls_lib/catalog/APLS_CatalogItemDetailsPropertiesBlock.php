<?php
class APLS_CatalogItemDetailsPropertiesBlock {

    private $property = array();
    private $html = "";
    private $idBlock = 32;// id инфоблока (Реквизиты товара)
    private $dataProductDetails;// ин-фа инфоблока (Реквизиты товара)
	const TRUE_VALUE = "Да";

    /**
     * APLS_CatalogItemDetailsPropertiesBlock constructor.
     * @param array $property
     */
    public function __construct(array $property) {
        $this->property = $property;
        if (CModule::IncludeModule('iblock')) {
            $this->getDataProductDetails();
            $this->generationBlockProductDetails();
        }
    }

    public function get() {
        echo $this->html;
    }

    public function getHtml() {
        return $this->html;
    }

    private function getDataProductDetails() {
        $arSort= Array("PROPERTY_SEQUENCE"=>"ASC");
        $arFilter = Array("IBLOCK_ID"=>$this->idBlock, "ACTIVE"=>"Y");
        $arSelect = Array(
            "CODE",
            "PROPERTY_SEQUENCE",
            "PROPERTY_TEXT",
	        "PROPERTY_ADRESS",
            "PROPERTY_CSSICON"
        );
        $res =  CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()) {
            $temp = $ob->GetFields();
            if (isset($this->property[$temp['CODE']]) && $this->property[$temp['CODE']]["VALUE"] == self::TRUE_VALUE) {
                $this->dataProductDetails[$temp['CODE']] = $temp;
            }
        }

    }

    private function generationBlockProductDetails() {
        $this->html .= "<div class='productDetailsWrapper advantages'>";
        foreach ($this->dataProductDetails as $value) {
            $cssStyleIcon = $value['PROPERTY_CSSICON_VALUE'];
	        $adres = $value['PROPERTY_ADRESS_VALUE'];
            $this->html .= "<div class='productDetails'>";
                $this->html .= '<a class="productDetailsIcons fa '.$cssStyleIcon .'" href="'.$adres.'">';
                $this->html .= "</a>";
//                $this->html .= "<div class='productDetailsText adv-text'>".$value['PROPERTY_TEXT_VALUE']."</div>";
                $this->html .= "<div class='clear'></div>";
            $this->html .= "</div>";
        }
        $this->html .= "</div>";
    }
}