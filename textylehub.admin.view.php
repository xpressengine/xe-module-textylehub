<?php
    /**
     * @class  textylehub
     * @author zero (skklove@gmail.com)
     * @brief  textylehub 모듈의 admin view class
     **/

    class textylehubAdminView extends ModuleObject {

        function init() {
            $oTextyleHubModel = &getModel('textylehub');
            $this->module_info = $oTextyleHubModel->getTextyleHubInfo();
            Context::set('module_info', $this->module_info);
            Context::set('module_srl', $this->module_info->module_srl);

            $this->setTemplatePath($this->module_path.'tpl');

			$security = new Security();
			$security->encodeHTML('module_info.');
        }

        function dispTextylehubAdminConfig() {

            $oModuleModel = &getModel('module');
            $skin_list = $oModuleModel->getSkins($this->module_path);
            Context::set('skin_list',$skin_list);

            $oLayoutMode = &getModel('layout');
            $layout_list = $oLayoutMode->getLayoutList();
            Context::set('layout_list', $layout_list);

            if(!$this->module_info->module_srl) $this->setTemplateFile('create');
            else $this->setTemplateFile('config');
        }

        function dispTextylehubAdminGrantInfo() {
            $oModuleAdminModel = &getAdminModel('module');
            $grant_content = $oModuleAdminModel->getModuleGrantHTML($this->module_info->module_srl, $this->xml_info->grant);
            Context::set('grant_content', $grant_content);

            $this->setTemplateFile('grant_list');
        }

        function dispTextylehubAdminSkinInfo() {
            $oModuleAdminModel = &getAdminModel('module');
            $skin_content = $oModuleAdminModel->getModuleSkinHTML($this->module_info->module_srl);
            Context::set('skin_content', $skin_content);

            $this->setTemplateFile('skin_info');
        }

    }
?>
