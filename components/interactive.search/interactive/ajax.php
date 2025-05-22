<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
$APPLICATION->RestartBuffer();
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
Loader::includeModule('iblock');
Loader::includeModule('search');

$searchQuery = htmlspecialchars(trim($_GET['q'] ?? ''));
$iblockId = 1;

$results = [
    'sections' => [],
    'elements' => [],
];
$cacheTtl = 60*60*24*3;
$cacheId = md5('search_results_'.$searchQuery);
$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTtl, $cacheId, '/search_results/')) {
    $results = $cache->getVars();
} elseif ($cache->startDataCache()) {
    if (!empty($searchQuery)) {
        $search = new CSearch();
        $search->Search([
            'QUERY' => $searchQuery,
            'SITE_ID' => "s1",
            'MODULE_ID' => 'iblock',
            'PARAM2' => 1,
        ]);

        if (!$search->selectedRowsCount()) {
            $search->Search(array(
                'QUERY' => $searchQuery,
                'SITE_ID' => 's1',
                'MODULE_ID' => 'iblock',
                'PARAM2' => 1
            ), array(), array('STEMMING' => false));
        }

        while ($item = $search->GetNext()) {

            if (strpos($item['ITEM_ID'], "S") !== false) {
                if(count($results['sections'])>=7) continue;
                $sectionId = (int)substr($item['ITEM_ID'], 1);

                $section = CIBlockSection::GetList(
                    [],
                    ['ID' => $sectionId],
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL']
                )->GetNext();

                if ($section)
                    $results['sections'][] = $section;
            } else {
                if(count($results['elements'])>=11) continue;
                $elementId = (int)$item['ITEM_ID'];

                $element = CIBlockElement::GetList(
                    [],
                    ['ID' => $elementId, 'CHECK_PERMISSIONS' => 'Y'],
                    false,
                    false,
                    ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL']
                )->GetNext();

                if ($element) {
                    $results['elements'][] = $element;
                }
            }
        }
    }

    $cache->endDataCache($results);
}

/*



$results = [
	'sections' => [],
	'elements' => []
];

if (!empty($searchQuery)) {
	$arFilter = [
		'%NAME' => $searchQuery,
		'ACTIVE' => 'Y',
		'IBLOCK_ID' => $iblockId,
	];
	$elementSelect = [
		'ID',
		'IBLOCK_ID',
		'NAME',
		'DETAIL_PAGE_URL',
	];
	$elementRes = CIBlockElement::GetList(
		[],
		$arFilter,
		false,
		false,
		$elementSelect
	);
	while ($element = $elementRes->GetNext()) {
		$results['elements'][] = $element;
	}

	$sectionRes = CIBlockSection::GetList(
		[],
		$arFilter,
		false,
		['ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL']
	);

	while ($section = $sectionRes->GetNext()) {
		$results['sections'][] = $section;
	}
}
dd($results);
*/
if( !empty($results['sections']) || !empty($results['elements'])):?>
    <div class="search-result">
        <? foreach ($results['sections'] as $el): ?>
            <div>
                <a href="<?= $el['SECTION_PAGE_URL'] ?>"><?= $el['NAME'] ?></a>
            </div>
        <? endforeach; ?>
        <?=!empty($results['sections'])?"<hr>":"";?>
        <? foreach ($results['elements'] as $el): ?>
            <div>
                <a href="<?= $el['DETAIL_PAGE_URL'] ?>"><?= $el['NAME'] ?></a>
            </div>
        <? endforeach; ?>
    </div>
<? endif;?>