<?php

namespace ProjectModel;

require_once("model.php");
require_once("utils.php");

//ini_set('error_reporting', E_ALL & ~E_NOTICE);

require_once("./input/atom.php");

$output_dir = "output/";
$server_src_dir = "src";
if (!file_exists($output_dir)) {
    mkdir($output_dir, 0777, true);
}

update_version_file($output_dir);

foreach (Model::$model['ProjectModel\Atom'] as $atom) {
    create_file($output_dir, "{$server_src_dir}/client/script/atom/", "{$atom->fileName}.js", process_template('artifact/client/script/atom/atom.js.php'));
}

// $components = Model::$model['ProjectModel\Component'];
// create_file($output_dir, $server_src_dir . 'dev/component.ts/', 'webpack.config.js', process_template('artifact/dev/component.ts/webpack.config.js.php'));

// foreach ($components as $component) {

//     $prefix = '';
//     if ($component->root) {
//         $prefix = '';
//     } else if ($component->shared || $component->subShared) {
//         $prefix = 'shared/';
//     } else if ($component->widget) {
//         $prefix = 'widget/';
//     } else {
//         $prefix = $component->GetFullFileName(true);
//     }

//     if ($component->root) {
//         create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", 'index.tsx', process_template('artifact/dev/component.ts/src/_name_/index.tsx.php'));
//     }

//     create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", "{$component->className}.tsx", process_template('artifact/dev/component.ts/src/_name_/component.tsx.php'));

//     if ($component->scss) {
//         create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", "{$component->className}.scss", process_template('artifact/dev/component.ts/src/_name_/component.scss.php'));
//     }

// }

// $actions = Model::$model['ProjectModel\Action'];
// create_file($output_dir, "{$server_src_dir}www/api2/", 'request.php', process_template('artifact/www/api2/request.php.php'));
// create_file($output_dir, "{$server_src_dir}www/api2/", 'checkrequest.php', process_template('artifact/www/api2/checkrequest.php.php'));

// $fileActions = [];
// foreach ($actions as $action) {
//     $fileName = $action->fileName;
//     if (!array_key_exists($fileName, $fileActions)) {
//         $fileActions[$fileName] = [];
//     }
//     $fileActions[$fileName][] = $action;
// }

// foreach ($fileActions as $fileName => $fileAction) {
//     create_file($output_dir, "{$server_src_dir}www/api2/action/", "{$fileName}.php", process_template('artifact/www/api2/action/action.php.php'));
//     create_file($output_dir, "{$server_src_dir}app/service2/", ucfirst($fileName) . "Service.php", process_template('artifact/app/service2/service.php.php'));
// }

echo 'Done ' . (date_create('now', timezone_open('Europe/Moscow')))->format('Y-m-d H:i') . PHP_EOL;





return;

$app_name = 'app';


// Widget
// ------
require_once("./widget/smallSpinnerProps.php");
require_once("./widget/inputProps.php");
require_once("./widget/input.php");
require_once("./widget/inputMultilingualProps.php");
require_once("./widget/inputMultilingual.php");
require_once("./widget/menuIcon.php");
require_once("./widget/textarea.php");
require_once("./widget/textareaProps.php");
require_once("./widget/selectProps.php");
require_once("./widget/select.php");
require_once("./widget/selectMultilingualProps.php");
require_once("./widget/selectMultilingual.php");
require_once("./widget/radioProps.php");
require_once("./widget/radio.php");
require_once("./widget/checkboxProps.php");
require_once("./widget/checkbox.php");
require_once("./widget/file.php");

// Shared
// ---------
require_once("./shared/layoutMain.php");
require_once("./shared/layoutHorz.php");
require_once("./shared/layoutForm.php");
require_once("./shared/layoutTabs.php");

require_once("./shared/modal.php");

require_once("./shared/spinner.php");

require_once("./shared/langSwitcher.php");
require_once("./shared/headerEmployee.php"); // --> langSwitcher
require_once("./shared/headerUser.php"); // --> langSwitcher
require_once("./shared/simpleTitle.php");
require_once("./shared/titleWithText.php");
require_once("./shared/titleWithTextAndButton.php");
require_once("./shared/alert.php");
require_once("./shared/table.php");
require_once("./shared/checkboxFrame.php");
require_once("./shared/questionView.php");
require_once("./shared/questionChooser.php");
require_once("./shared/countdownTimer.php");
require_once("./shared/chat.php");
require_once("./shared/surveyView.php");
require_once("./shared/surveyBaseForm.php");
require_once("./shared/testHelp.php");
require_once("./shared/location.php");

// Actions
// ----
require_once './action/getShortUserInfo.php';
require_once './action/getAdminList.php';
require_once './action/getAdminRightList.php';
require_once './action/setAdminRightList.php';
require_once './action/getEmployeeRightList.php';
require_once './action/getEmployeeCourseList.php';
require_once './action/getEmployeeCourseById.php';
require_once './action/saveEmployeeCourse.php';
require_once './action/getEmployeeViewCourseById.php';
require_once './action/getStudentCourseList.php';
require_once './action/getStudentCourseInfo.php';
require_once './action/getEmployeeFinalTestCourseById.php';
require_once './action/checkFinalTest.php';
require_once './action/getStudentCourseById.php';
require_once './action/getStudentLesson.php';
require_once './action/getStudentFinalTestCourseById.php';
require_once './action/getStudentCuratorChat.php';
require_once './action/sendMessageToStudentCuratorChat.php';
require_once './action/sendFileToStudentCuratorChat.php';
require_once './action/downloadFileFromStudentCuratorChat.php';
require_once './action/closeStudentCuratorChat.php';
require_once './action/getEmployeeChatCourseById.php';
require_once './action/getEmployeeCuratorChat.php';
require_once './action/closeEmployeeCuratorChat.php';
require_once './action/sendMessageToEmployeeCuratorChat.php';
require_once './action/sendFileToEmployeeCuratorChat.php';
require_once './action/downloadFileFromEmployeeCuratorChat.php';
require_once './action/getAdminCampList.php';
require_once './action/getAdminCampById.php';
require_once './action/getSurveyBase.php';
require_once './action/saveSurveyBase.php';
require_once './action/saveAdminCamp.php';
require_once './action/checkProfileForCamp.php';
require_once './action/getStudentCampList.php';
require_once './action/getStudentCampRequestById.php';
require_once './action/saveStudentCampRequest.php';
require_once './action/downloadCampRequestListReport.php';
require_once './action/downloadELearningCertificate.php';
require_once './action/setCourseCompletedConfirmFlag.php';
require_once './action/getAdminSettings.php';
require_once './action/saveAdminSettings.php';
require_once './action/runClassRecalculation.php';
require_once './action/getStudentMenuModalMessage.php';
require_once './action/getEmployeeCampList.php';
require_once './action/getEmployeeCampById.php';
require_once './action/getEmployeeCampRights.php';
require_once './action/getCampContractListForEmployee.php';
require_once './action/getCampContractForEmployee.php';
require_once './action/downloadCampContract.php';
require_once './action/downloadCampDraftContract.php';
require_once './action/updateCampContract.php';
require_once './action/removeCampRequest.php';
require_once './action/uploadSignedCampContract.php';
require_once './action/downloadCampSignedContract.php';
require_once './action/setAcceptForCampContract.php';
require_once './action/getCampPaymentListForEmployee.php';
require_once './action/addCampContractPayment.php';
require_once './action/showCancelCampButton.php';
require_once './action/downloadCancelCampStatement.php';
require_once './action/downloadSignedCancelCampStatementById.php';
require_once './action/setCancelCampStatus.php';
require_once './action/uploadSignedCampCancelContract.php';
require_once './action/downloadCampPaymentListReport.php';
require_once './action/getAdminCheckpointList.php';
require_once './action/getAdminCheckpointById.php';
require_once './action/saveAdminCheckpoint.php';
require_once './action/getCountryList.php';
require_once './action/getRegionListByCountryId.php';
require_once './action/getCityListByRegionId.php';
require_once './action/getAdminStaffCampById.php';
require_once './action/saveAdminCampStaff.php';
require_once './action/getCampProfileInfoForEmployee.php';
require_once './action/getCampStudentListForEmployee.php';
require_once './action/saveCampProfileInfo.php';
require_once './action/saveCampStudentNote.php';
require_once './action/saveCampHonourCert.php';
require_once './action/getCampStudentProfileListForEmployee.php';
require_once './action/updateStudentProfileInfo.php';
require_once './action/getStudentCampById.php';
require_once './action/saveStudentCampProfile.php';
require_once './action/getCampLogisticsStudentListForEmployee.php';
require_once './action/setStudentLogisticApproval.php';
require_once './action/getLogisticsSupportStudentListForEmployee.php';
require_once './action/downloadLogisticSupportReport.php';
require_once './action/downloadCampGIBDDListReport.php';
require_once './action/downloadCampCertificate.php';
require_once './action/downloadCampCertificateList.php';
require_once './action/getEmployeeCampGroupListByCampId.php';
require_once './action/getCampGroup.php';
require_once './action/saveCampGroup.php';
require_once './action/delCampGroup.php';
require_once './action/downloadCampGroup.php';
require_once './action/downloadCampCertificateEmplList.php';
require_once './action/downloadCampAcceptedRequestListReport.php';

// Page
// ----
require_once './page/rootAdminList.php';
require_once './page/adminMenu.php';
require_once './page/employeeMenu.php';
require_once './page/employeeELearningList.php';
require_once './page/employeeELearningView.php';
require_once './page/studentELearningList.php';
require_once './page/studentMenu.php';
require_once './page/studentELearningInfo.php';
require_once './page/studentELearning.php';
require_once './page/employeeELearningFinalTestView.php';
require_once './page/studentELearningFinalTestView.php';
require_once './page/employeeELearningChat.php';
require_once './page/studentCampingList.php';
require_once './page/adminCampingList.php';
require_once './page/adminSettings.php';
require_once './page/employeeCampingList.php';
require_once './page/employeeContractCampingList.php';
require_once './page/employeePaymentCampingList.php';
require_once './page/adminCheckpointList.php';
require_once './page/employeeCampGroupList.php';


$output_dir = "output/";
$server_src_dir = "src/";
if (!file_exists($output_dir)) {
    mkdir($output_dir, 0777, true);
}

update_version_file($output_dir);

foreach (Model::$model['ProjectModel\Page'] as $page) {
    create_file($output_dir, "{$server_src_dir}www/", "{$page->fileName}.php", process_template('artifact/www/page.php.php'));
    create_file($output_dir, "{$server_src_dir}www/js2/", "{$page->fileName}.js", process_template('artifact/www/js2/page.js.php'));
    create_file($output_dir, "{$server_src_dir}app/template2/page/", "{$page->fileName}.php", process_template('artifact/app/template2/page/page.php.php'));
}

$components = Model::$model['ProjectModel\Component'];
create_file($output_dir, $server_src_dir . 'dev/component.ts/', 'webpack.config.js', process_template('artifact/dev/component.ts/webpack.config.js.php'));

foreach ($components as $component) {

    $prefix = '';
    if ($component->root) {
        $prefix = '';
    } else if ($component->shared || $component->subShared) {
        $prefix = 'shared/';
    } else if ($component->widget) {
        $prefix = 'widget/';
    } else {
        $prefix = $component->GetFullFileName(true);
    }

    if ($component->root) {
        create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", 'index.tsx', process_template('artifact/dev/component.ts/src/_name_/index.tsx.php'));
    }

    create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", "{$component->className}.tsx", process_template('artifact/dev/component.ts/src/_name_/component.tsx.php'));

    if ($component->scss) {
        create_file($output_dir, "{$server_src_dir}dev/component.ts/src/{$prefix}{$component->folderName}/", "{$component->className}.scss", process_template('artifact/dev/component.ts/src/_name_/component.scss.php'));
    }

}

$actions = Model::$model['ProjectModel\Action'];
create_file($output_dir, "{$server_src_dir}www/api2/", 'request.php', process_template('artifact/www/api2/request.php.php'));
create_file($output_dir, "{$server_src_dir}www/api2/", 'checkrequest.php', process_template('artifact/www/api2/checkrequest.php.php'));

$fileActions = [];
foreach ($actions as $action) {
    $fileName = $action->fileName;
    if (!array_key_exists($fileName, $fileActions)) {
        $fileActions[$fileName] = [];
    }
    $fileActions[$fileName][] = $action;
}

foreach ($fileActions as $fileName => $fileAction) {
    create_file($output_dir, "{$server_src_dir}www/api2/action/", "{$fileName}.php", process_template('artifact/www/api2/action/action.php.php'));
    create_file($output_dir, "{$server_src_dir}app/service2/", ucfirst($fileName) . "Service.php", process_template('artifact/app/service2/service.php.php'));
}

$date = date_create(null, timezone_open('Europe/Moscow'));
echo 'Done ' . $date->format('Y-m-d H:i') . PHP_EOL;
