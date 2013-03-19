<?php
    /**
     * @class  textylehub
     * @author zero (skklove@gmail.com)
     * @brief  textylehub 모듈의 view class
     **/

    class textylehubView extends ModuleObject {

        function init() {
            $oTextyleHubModel = &getModel('textylehub');
            $this->module_info = $oTextyleHubModel->getTextyleHubInfo();
            Context::set('module_info', $this->module_info);
            Context::set('module_srl', $this->module_info->module_srl);

            $skin= $this->module_info->skin;
            if(!$skin) $skin = 'default';
            $skin_path = $this->module_path.'skins/'.$skin;
            if(!is_dir($skin_path)) $skin_path = $this->module_path.'skins/'.$skin;
            $this->setTemplatePath($skin_path);
        }

        function dispTextylehub() {
            $oDocumentModel = &getModel('document');

            $page = (int)Context::get('page');
            if(!$page) $page = 1;
            Context::set('page', $page);

            $hub = $this->getTextyleSubContent();

            // 최근글 구함
            $args->page = $page;
            $args->page_count = 10;
            $args->list_count = $this->module_info->newest_documents_count;
			$args->status = 'PUBLIC';	//2011.03.04 비밀글 안뽑아오기 - cherryfilter

            $search_target = trim(Context::get('search_target'));
            $search_keyword = trim(Context::get('search_keyword'));
            if(in_array($search_target,array('title','content','tag')) && $search_keyword) {
                $search_keyword = explode(' ',$search_keyword);
                for($i=0,$c=count($search_keyword);$i<$c;$i++) {
                    $k = trim($search_keyword[$i]);
                    if(!$k) continue;
                    $sk[] = $k;
                }
                if(count($sk)) $args->{'s_'.$search_target} = implode('%', $sk);
            }
            $output = executeQueryArray('textylehub.getNewestPost',$args);
            if($output->data) {
                foreach($output->data as $key => $attribute) {
                    $document_srl = $attribute->document_srl;
                    if(!$GLOBALS['XE_DOCUMENT_LIST'][$document_srl]) {
                        $oDocument = null;
                        $oDocument = new documentItem();
                        $oDocument->setAttribute($attribute, false);
                        if($is_admin) $oDocument->setGrant();
                        $GLOBALS['XE_DOCUMENT_LIST'][$document_srl] = $oDocument;
                    }

                    $output->data[$key] = $GLOBALS['XE_DOCUMENT_LIST'][$document_srl];
                }
            }
            $oDocumentModel->setToAllDocumentExtraVars();

            $hub->posts = $output->data;
            Context::set('page_navigation', $output->page_navigation);

            Context::set('hub', $hub);

            $this->setTemplateFile('index');
        }

        function dispTextylehubUpdated() {
            $oTextyleModel = &getModel('textyle');
            $oDocumentModel = &getModel('document');

            $page = (int)Context::get('page');
            if(!$page) $page = 1;
            Context::set('page', $page);

            $hub = $this->getTextyleSubContent();

            $args->page = $page;
            $args->page_count = 10;
            $args->list_count = $this->module_info->newest_textyles_count;
            $output = executeQueryArray('textylehub.getTextyles',$args);
            Context::set('page_navigation', $output->page_navigation);

            if(count($output->data)) {
                foreach($output->data as $key => $val) {
                    $val->photo_src = $oTextyleModel->getTextylePhotoSrc($val->member_srl);
                    $hub->textyles[$val->module_srl] = $val;
                }

                $module_srls = array_keys($hub->textyles);

                if(count($module_srls)) {
                    unset($args);
                    $args->module_srls = implode(',',$module_srls);

                    // 게시글 수 구하기
                    $output = executeQueryArray('textylehub.getTextyleDocumentCount', $args);
                    if($output->data) {
                        foreach($output->data as $key => $val) {
                            $hub->textyles[$val->module_srl]->document_count = $val->count;
                        }
                    }

                    // 댓글 수 구하기
                    $output = executeQueryArray('textylehub.getTextyleCommentCount', $args);
                    if($output->data) {
                        foreach($output->data as $key => $val) {
                            $hub->textyles[$val->module_srl]->comment_count = $val->count;
                        }
                    }

                    // 엮인글 수 구하기
                    $output = executeQueryArray('textylehub.getTextyleTrackbackCount', $args);
                    if($output->data) {
                        foreach($output->data as $key => $val) {
                            $hub->textyles[$val->module_srl]->trackback_count = $val->count;
                        }
                    }

                }
            }

            // 최근글 구함
            unset($args);
            $args->list_count = $this->module_info->sub_newest_textyles_count;
            $output = executeQueryArray('textylehub.getNewestPost',$args);
            if($output->data) {
                foreach($output->data as $key => $attribute) {
                    $document_srl = $attribute->document_srl;
                    if(!$GLOBALS['XE_DOCUMENT_LIST'][$document_srl]) {
                        $oDocument = null;
                        $oDocument = new documentItem();
                        $oDocument->setAttribute($attribute, false);
                        if($is_admin) $oDocument->setGrant();
                        $GLOBALS['XE_DOCUMENT_LIST'][$document_srl] = $oDocument;
                    }

                    $output->data[$key] = $GLOBALS['XE_DOCUMENT_LIST'][$document_srl];
                }
            }
            $oDocumentModel->setToAllDocumentExtraVars();
            $hub->newest_posts = $output->data;

            Context::set('hub', $hub);

            $this->setTemplateFile('textyle_list');
        }

        function getTextyleSubContent() {
            $oTextyleModel = &getModel('textyle');
            $oCommentModel = &getModel('comment');

            $is_logged = Context::get('is_logged');
            $logged_info = Context::get('logged_info');

            // get created textyle count for administrator
            if($logged_info->is_admin == 'Y') {
                $output = executeQuery('textylehub.getTextyleCount');
                $hub->total_textyle_count = (int)($output->data->count);
            }

            // get logged user's own textyle
            if($is_logged) {
                $args->member_srl = $logged_info->member_srl;
                $output = executeQueryArray('textylehub.getOwnTextyle', $args);
                $hub->own_textyle = $output->data;
                $hub->own_textyle_count = count($output->data);;
            } else {
                $hub->own_textyle_count = 0;
            }

            // check creation grant
            $hub->enable_creation = $this->grant->create;
            if($logged_info->is_admin != 'Y' && (!$hub->enable_creation || $this->module_info->textyle_creation_count<=$hub->own_textyle_count)) $hub->enable_creation = false;

            // newest comments
            unset($args);
            $args->list_count = $this->module_info->newest_comments_count;
            $output = executeQueryArray('textylehub.getNewestComment',$args);
            if($output->data) {
                foreach($output->data as $key => $val) {
                    unset($oComment);
                    $oComment = $oCommentModel->getComment();
                    $oComment->setAttribute($val);
                    $hub->newest_comment[] = $oComment;
                }
            }

            // newest trackbacks
            unset($args);
            $args->list_count = $this->module_info->newest_trackbacks_count;
            $output = executeQueryArray('textylehub.getNewestTrackback',$args);
            $hub->newest_trackback = $output->data;

            // 태그 추출
            $args->list_count = $list_cnt;
            $output = executeQueryArray('textylehub.getPopularTags',$args);
            if($output->data) {
                $tags = array();
                $max = 0;
                $min = 99999999;
                foreach($output->data as $key => $val) {
                    $tag = trim($val->tag);
                    if(!$tag) continue;
                    $count = $val->count;
                    if($max < $count) $max = $count;
                    if($min > $count) $min = $count;
                    $tags[] = $val;
                }
                $mid2 = $min+(int)(($max-$min)/2);
                $mid1 = $mid2+(int)(($max-$mid2)/2);
                $mid3 = $min+(int)(($mid2-$min)/2);

                foreach($tags as $key => $item) {
                    if($item->count > $mid1) $rank = 1;
                    elseif($item->count > $mid2) $rank = 2;
                    elseif($item->count > $mid3) $rank = 3;
                    else $rank= 4;

                    $tags[$key]->rank = $rank;
                }
                shuffle($tags);
                $hub->tags = $tags;
            }

            // 업데이트 된 블로그 구함
            unset($args);
            $args->list_count = $this->module_info->sub_newest_textyles_count;
            $output = executeQueryArray('textylehub.getUpdatedTextyle',$args);
            if(count($output->data)) {
                foreach($output->data as $key => $val) {
                    $document_srls[] = $val->document_srl;
                }
                if(count($document_srls)) {
                    unset($args);
                    $args->document_srls = implode(',',$document_srls);
                    $output = executeQueryArray('textylehub.getUpdatedTextyles', $args);
                    if(count($output->data)) {
                        foreach($output->data as $key => $val) {
                            $val->photo_src = $oTextyleModel->getTextylePhotoSrc($val->member_srl);
                            $hub->updated_textyles[] = $val;
                        }
                    }
                }
            }
            return $hub;
        }

        function dispTextylehubCreate() {
            $hub = $this->getTextyleSubContent();
            Context::set('hub', $hub);

            if(!$hub->enable_creation) return new Object(-1,'msg_disable_to_create_textyle');

            Context::addJsFilter($this->module_path.'tpl/filter', 'creation.xml');
            $this->setTemplateFile('creation');
        }

        function rss() {
            $oRss = &getView('rss');
            $oDocumentModel = &getModel('document');
            $oModuleModel = &getModel('module');

            $output = executeQueryArray('textylehub.getRssList', $args);
            if($output->data) {
                foreach($output->data as $key => $val) {
                    unset($obj);
                    $obj = new DocumentItem(0);
                    $obj->setAttribute($val);
                    $document_list[] = $obj;
                }
            }

            $oRss->rss($document_list, $this->module_info->browser_title);
            $this->setTemplatePath($oRss->getTemplatePath());
            $this->setTemplateFile($oRss->getTemplateFile());
        }
    }
?>
