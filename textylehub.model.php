<?php
    /**
     * @class  textylehub
     * @author zero (skklove@gmail.com)
     * @brief  textylehub 모듈의 model class
     **/

    class textylehubModel extends ModuleObject {

        function getTextyleHubInfo() {
            $oModuleModel = &getModel('module');

            $output = executeQuery('textylehub.getTextyleHub');
            if(!$output->data->module_srl) return;

            $module_info = $oModuleModel->getModuleInfoByModuleSrl($output->data->module_srl);
            if(!$module_info->textyle_creation_count) $module_info->textyle_creation_count = 1;
            if(!$module_info->newest_documents_count) $module_info->newest_documents_count = 20;
            if(!$module_info->newest_textyles_count) $module_info->newest_textyles_count = 10;
            if(!$module_info->sub_newest_textyles_count) $module_info->sub_newest_textyles_count = 5;
            if(!$module_info->newest_comments_count) $module_info->newest_comments_count = 5;
            if(!$module_info->newest_trackbacks_count) $module_info->newest_trackbacks_count = 5;
            return $module_info;
        }
    }
?>
