<?

//$excelFilePath = \COption::GetOptionString("main", "excel_file_path", "");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

\Bitrix\Main\Loader::includeModule('main');

if(!$USER->IsAdmin()) {
    $APPLICATION->AuthForm("Доступ запрещен");
}

$APPLICATION->SetTitle("Загрузка Excel-файла");

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file']) && check_bitrix_sessid()) {

    $file = $_FILES['excel_file'];

    if($file['error'] === UPLOAD_ERR_OK) {
        // Проверка расширения файла
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if($fileExt !== 'xls' && $fileExt !== 'xlsx') {
            $errorMessage = "Пожалуйста, загрузите файл в формате Excel (.xls или .xlsx)";
        } else {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/upload/excel_files/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'excel_upload_'.date('Ymd_His').'.'.$fileExt;
            $filePath = $uploadDir.$fileName;

            if(move_uploaded_file($file['tmp_name'], $filePath)) {

                \COption::SetOptionString("main", "excel_file_path", $filePath);

                $successMessage = "Файл успешно загружен и сохранен!";
            } else {
                $errorMessage = "Ошибка при сохранении файла";
            }
        }
    } else {
        $errorMessage = "Ошибка при загрузке файла: ".$file['error'];
    }
}
$currentFilePath = \COption::GetOptionString("main", "excel_file_path", "");
?>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>

<?if(isset($successMessage)):?>
    <div class="adm-info-message-wrap">
        <div class="adm-info-message"><?=$successMessage?></div>
    </div>
<?endif;?>

<?if(isset($errorMessage)):?>
    <div class="adm-info-message-wrap adm-info-message-error">
        <div class="adm-info-message"><?=$errorMessage?></div>
    </div>
<?endif;?>
    <form method="post" enctype="multipart/form-data">
        <?=bitrix_sessid_post()?>

        <div class="adm-detail-content">
            <div class="adm-detail-title">Загрузка Excel-файла для подборки мотор-редуктора на <a href="/catalog/selection/">странице</a> </div>

            <div class="adm-detail-content-item-block">
                <div class="adm-detail-content-item">
                    <div class="adm-detail-content-item-title">Выберите файл Excel:</div>
                    <div class="adm-detail-content-item-field">
                        <input type="file" name="excel_file" accept=".xls,.xlsx" required>
                    </div>
                </div>

                <?if($currentFilePath):?>
                    <div class="adm-detail-content-item">
                        <div class="adm-detail-content-item-title">Текущий загруженный файл:</div>
                        <div class="adm-detail-content-item-field">
                            <?=htmlspecialcharsbx($currentFilePath)?>
                        </div>
                    </div>
                <?endif;?>
            </div>

            <div class="adm-detail-content-btns">
                <input type="submit" name="upload" value="Загрузить" class="adm-btn adm-btn-save">
            </div>
        </div>
    </form>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>