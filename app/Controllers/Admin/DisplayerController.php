<?php

namespace App\Controllers\Admin;

use Faker\Factory;
use Swoft\Admin\Admin;
use Swoft\Admin\Grid\Displayers\Editable;
use Swoft\Admin\Grid\Displayers\Expand;
use Swoft\Admin\Grid\Model;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Support\Contracts\Renderable;

/**
 * @Controller("/admin/displayer")
 */
class DisplayerController
{
    /**
     * @RequestMapping("/admin/displayer")
     */
    public function index(Content $content)
    {
        $header = Admin::translateLabel('Displayers');

        return $content
            ->body($this->grid())
            ->breadcrumb($header)
            ->header($header)
            ->response();
    }

    protected function grid()
    {
        $grid = Admin::grid();

        $grid->disableCreation();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableBatchDelete();
        $grid->disablePagination();
        $grid->disableRefreshButton();
        $grid->disableFilter();
        $grid->disableExport();

        // 更改表格外层容器
        $grid->wrapper(function (Renderable $view) {
            $tab = new Tab();

            $tab->add(t('Example', 'admin'), $view);
            // 代码预览
            $tab->add(t('Code', 'admin'), new Code(__FILE__, 27, 123));

            return $tab;
        });

        $grid->id->color('#DD1144')->display(function ($value) {
            return "<b>$value</b>";
        });
        $grid->label->explode()->label();
        $grid->progressBar->progressBar();
        $grid->expand->expand(function (Expand $expand) {
            $faker = Factory::create();
            $expand->label($faker->name);
            $expand->content(new Card(null, $faker->text(900)));
        });
        $grid->switch->switch();
        $grid->editable->editable('select', $this->getNames());
        $grid->checkbox->checkbox(['GET', 'POST', 'PUT', 'DELETE']);
        $grid->radio->radio(['PHP', 'JAVA', 'GO', 'C']);

        $this->fetch($grid->model());

        return $grid;
    }

    /**
     * @RequestMapping(route="{id}", method={RequestMethod::POST})
     */
    public function update()
    {
        return [
            'status' => true,
            'message' => '模拟修改~',
        ];
    }

    /**
     * 生成假数据
     *
     * @param Model $model
     */
    public function fetch(Model $model) {
        $faker = Factory::create();

        $data = [];

        for ($i = 0; $i < 6; $i++) {
            $data[] = [
                'id' => $i+1,
                'label' => str_repeat($faker->name().',', mt_rand(1, 2)),
                'progressBar' => mt_rand(1, 100),
                'switch' => mt_rand(0, 1),
                'editable' => mt_rand(0, 14),
                'checkbox' => value(function () use ($faker) {
                    $values = [];
                    for ($i = 0; $i < mt_rand(1, 4); $i++) {
                        $values[] = mt_rand(0, 3);
                    }
                    return join(',', $values);
                }),
                'radio' => mt_rand(0, 3),
            ];
        }

        $model->setData($data);
    }

    /**
     * 生成假数据
     *
     * @return array
     */
    protected function getNames()
    {
        $faker = Factory::create();

        $data = [];
        for ($i = 0; $i < 15; $i++) {
            $data[] = $faker->name;
        }
        return $data;
    }
}
