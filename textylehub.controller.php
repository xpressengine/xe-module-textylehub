<?php
    /**
     * @class  textylehub
     * @author zero (skklove@gmail.com)
     * @brief  textylehub 모듈의 controller class
     **/

    class textylehubController extends ModuleObject {

        function init() {
            $oTextyleHubModel = &getModel('textylehub');
            $this->module_info = $oTextyleHubModel->getTextyleHubInfo();
            Context::set('module_info', $this->module_info);
        }

        function procTextylehubCreate() {
            $oModuleModel = &getModel('module');
            $oTextyleAdminController = &getAdminController('textyle');
            $oMemberModel = &getModel('member');

            $logged_info = Context::get('logged_info');
            if(!$logged_info) return new Object(-1,'msg_invalid_request');

            // before 트리거 호출
            $obj = Context::getRequestVars();
            $trigger_output = ModuleHandler::triggerCall('textylehub.procTextylehubCreate', 'before', $obj);
            if(!$trigger_output->toBool()) return $trigger_output;

            $domain = Context::get('textyle_address');
            $title = Context::get('blog_title');
            $description = Context::get('blog_description');
            $accept_agree = Context::get('accept_agree');

            if($accept_agree != 'Y') return new Object(-1,'about_textyle_agreement');

            if(!$domain) return new Object(-1,'alert_textyle_address_is_null');
            if(strlen($domain)<4 || strlen($domain)>12) return new Object(-1,'alert_textyle_address_is_wrong');
            if(!preg_match('/^([a-z])([a-z0-9]+)$/i',$domain)) return new Object(-1,'alert_textyle_address_format');

            if($this->module_info->access_type != 'vid') {
                $domain = $domain.'.'.$this->module_info->default_domain;
            } else {
                if($oModuleModel->isIDExists($domain)) return new Object(-1,'alert_textyle_address_is_exists');
            }

            if(!$title) return new ObjecT(-1,'alert_textyle_title_is_null');

            if($logged_info->is_admin != 'Y') {
                $args->member_srl = $logged_info->member_srl;
                $output = executeQueryArray('textylehub.getOwnTextyle', $args);
                $own_textyle_count = count($output->data);
                if(!$this->grant->create || $this->module_info->textyle_creation_count<=$own_textyle_count) return new Object(-1,'alert_disable_to_create');
            }
		
            $settings = null;
            $settings->title = $title;
            
       		$memberConfig = $oMemberModel->getMemberConfig();
            foreach($memberConfig->signupForm as $item){
            	if($item->isIdentifier) $identifierName = $item->name;
            }
            if($identifierName == "user_id") {
            	$identifierValue = $logged_info->user_id;
            	}
            else {
            	$identifierValue = $logged_info->email_address;
            }
            // textyle 생성
            $output = $oTextyleAdminController->insertTextyle($domain, $identifierValue, $settings);
            if(!$output->toBool()) return $output;

            $module_srl = $output->get('module_srl');

            // 생성된 텍스타일의 제목/ 소개 변경
            $targs->textyle_title = $title;
            $targs->textyle_content = $description;
            $targs->module_srl = $module_srl;
            executeQuery('textyle.updateTextyle', $targs);

            $this->setRedirectUrl(getSiteUrl($domain));

            $trigger_args = $obj;
            $trigger_args->site_srl = $obj;
            // before 트리거 호출
            $obj = Context::getRequestVars();
            $trigger_output = ModuleHandler::triggerCall('textylehub.procTextylehubCreate', 'after', $obj);
            if(!$trigger_output->toBool()) return $trigger_output;
        }

    }
?>
