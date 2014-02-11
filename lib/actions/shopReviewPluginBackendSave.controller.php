<?php

class shopReviewPluginBackendSaveController extends waJsonController {

    protected $tmp_path = 'plugins/review/templates/actions/frontend/Frontend.html';
    protected $plugin_id = array('shop', 'review');

    public function execute() {
        try {
            $app_settings_model = new waAppSettingsModel();
            $shop_review = waRequest::post('shop_review');

            foreach ($shop_review as $key => $val)
                $app_settings_model->set($this->plugin_id, $key, $val);



            if (isset($shop_novelties['reset_tpl']) && $shop_novelties['reset_tpl']) {
                $template_path = wa()->getDataPath($this->tmp_path, false, 'shop', true);
                @unlink($template_path);
            } else {
                $post_template = waRequest::post('template');
                if (!$post_template) {
                    throw new waException('Не определён шаблон');
                }

                $template_path = wa()->getDataPath($this->tmp_path, false, 'shop', true);
                if (!file_exists($template_path)) {
                    $template_path = wa()->getAppPath($this->tmp_path, 'shop');
                }

                $template = file_get_contents($template_path);
                if ($template != $post_template) {
                    $template_path = wa()->getDataPath($this->tmp_path, false, 'shop', true);

                    $f = fopen($template_path, 'w');
                    if (!$f) {
                        throw new waException('Не удаётся сохранить шаблон. Проверьте права на запись ' . $template_path);
                    }
                    fwrite($f, $post_template);
                    fclose($f);
                }
            }

            $this->response['message'] = "Сохранено";
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

}
