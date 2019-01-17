<?php

namespace App\Controllers\Admin;

use Faker\Factory;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Admin\Admin;
use Swoft\Admin\Grid;
use Swoft\Admin\Layout\Content;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Support\Collection;
use Swoft\Support\Contracts\Renderable;

/**
 * @Controller("/admin/reports")
 */
class ReportsController
{
    /**
     * 列表页
     *
     * @RequestMapping(route="/admin/reports", method=RequestMethod::GET)
     *
     * @param Content $content
     * @return mixed
     */
    public function index(Content $content)
    {
        $grid = $this->grid();

        if ($response = $grid->export(function (Collection $records) {
            // 可以对待导出的内容进行过滤或加工
            return $records;
        })) {
            // 返回导出内容
            return $response;
        }
        $header = translate_label('Reports');

        // 添加面包屑导航
        $content->breadcrumb($header);

        return $content
            ->header($header)
            ->description(translate_label('List'))
            ->body($grid)
            ->response();
    }

   /**
    * 创建表格
    *
    * @return Grid
    */
    protected function grid()
    {
        $grid = new Grid();

        // 设置回调方法获取报表展示数据
        $this->fetch($grid->model());

        // 开启responsive插件
        $grid->responsive();
//        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableBatchDelete();
        $grid->disableCreation();

        // 更改表格外层容器
        $grid->wrapper(function (Renderable $view) {
            $tab = new Tab();

            $tab->add(t('Example', 'admin'), $view);
            // 代码预览
            $tab->add(t('Code', 'admin'), new Code(__FILE__, 30, 160));

            return $tab;
        });

        // 设置一级表头, 查看报表数据更清晰
        $grid->header('avgCost', ['avgMonthCost', 'avgQuarterCost', 'avgYearCost'])->responsive();
        $grid->header('avgVist', ['avgMonthVist', 'avgQuarterVist', 'avgYearVist'])->responsive();
        $grid->header('top', ['topCost', 'topVist', 'topIncr'])->responsive()->style('color:#1867c0');

        $grid->content->expand(null, true)->responsive();
        $grid->cost->sortable()->responsive()->color('#ff5b5b');
        $grid->avgMonthCost->responsive();
        $grid->avgQuarterCost->responsive()->setHeaderAttributes(['style' => 'color:#5b69bc']);
        $grid->avgYearCost->responsive();
        $grid->avgMonthVist->responsive();
        $grid->avgQuarterVist->responsive();
        $grid->avgYearVist->responsive();
        $grid->incrs->responsive();
        $grid->avgVists->responsive();
        $grid->topCost->responsive();
        $grid->topVist->responsive();
        $grid->topIncr->responsive();
        $grid->date->sortable()->responsive();
        
        $grid->filter(function (Grid\Filter $filter) {
            $filter->scope('time', function (Grid\Filter\Scopes $scopes) {
                $scopes->add(1, Admin::translateField('month'))->where('date', 2019, '<=');
                $scopes->add(2, Admin::translateField('quarter'))->where('date', 2019, '<=');
                $scopes->add(3, Admin::translateField('year'))->where('date', 2019, '<=');
            });

            $filter->equal('content');
        
        });

        return $grid;
    }

    /**
     * 这里生成假数据演示报表功能
     *
     * @param Grid\Model $model
     */
    public function fetch(Grid\Model $model)
    {
        $faker = Factory::create();

        $data = [];

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'content' => $faker->text,
                'cost' => $faker->randomFloat(),
                'avgMonthCost' => $faker->randomFloat(),
                'avgQuarterCost' => $faker->randomFloat(),
                'avgYearCost' => $faker->randomFloat(),
                'incrs' => $faker->numberBetween(1, 999999999),
                'avgMonthVist' => $faker->numberBetween(1, 999999),
                'avgQuarterVist' => $faker->numberBetween(1, 999999),
                'avgYearVist' => $faker->numberBetween(1, 999999),
                'avgVists' => $faker->numberBetween(1, 999999),
                'topCost' => $faker->numberBetween(1, 999999999),
                'topVist' => $faker->numberBetween(1, 9999990009),
                'topIncr' => $faker->numberBetween(1, 99999999),
                'date' => $faker->date(),
            ];
        }

        // 通过getQueries方法可以拿到查询条件
        // 此处是假数据, 无需处理查询条件
        $queries = $model->getQueries();

        $model->setData($data);
        $model->setTotal(200);
    }



}
