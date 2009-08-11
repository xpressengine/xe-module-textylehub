<?php
    /**
     * @class  textylehub
     * @author zero (skklove@gmail.com)
     * @brief  textylehub 모듈의 admin controller class
     **/

    class textylehubAdminController extends ModuleObject {

        function init() {
            $oTextyleHubModel = &getModel('textylehub');
            $this->module_info = $oTextyleHubModel->getTextyleHubInfo();
            Context::set('module_info', $this->module_info);
        }

        function procTextylehubAdminCreate() {
            $oModuleController = &getController('module');

            if($this->module_info->module_srl) return new Object(-1,'msg_invalid_request');

            $output = executeQuery('textylehub.getTextyleHub');
            $hub_info = $output->data;
            if($hub_info) return new Object(-1,'msg_invalid_request');

            $args->module = 'textylehub';
            $args->mid = Context::get('textylehub_id');
            $args->site_srl = 0;
            $args->skin = 'default';
            $args->browser_title = 'Textyle Hub';
            $output = $oModuleController->insertModule($args);
            if(!$output->toBool()) return $output;

            $this->setRedirectUrl(getUrl('','module','admin','act','dispTextylehubAdminConfig'));
        }

        function procTextylehubAdminUpdate() {
            if(!$this->module_info->module_srl) return new Object(-1,'msg_invalid_request');

            // module 모듈의 model/controller 객체 생성
            $oModuleController = &getController('module');
            $oModuleModel = &getModel('module');

            // 게시판 모듈의 정보 설정
            $args = Context::getRequestVars();
            $args->module = 'textylehub';
            $args->mid = $args->textylehub_id;
            unset($args->textylehub_id);

            // 기본 값외의 것들을 정리
            if(!$args->textyle_creation_count) $args->textyle_creation_count = 1;
            if(!$args->newest_documents_count) $args->newest_documents_count = 20;
            if(!$args->newest_textyles_count) $args->newest_textyles_count = 20;
            if(!$args->sub_newest_textyles_count) $args->sub_newest_textyles_count = 5;
            if(!$args->newest_comments_count) $args->newest_comments_count = 5;
            if(!$args->newest_trackbacks_count) $args->newest_trackbacks_count = 5;

            $args->module_srl = $this->module_info->module_srl;
            $output = $oModuleController->updateModule($args);
            if(!$output->toBool()) return $output;
        }

    }
?>
