<?php

namespace Botble\SeoBoost\Tables;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\YesNoColumn;

class IndexNowLogTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(IndexNowLog::class)
            ->setView('plugins/seo-boost::table')
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('url')
                    ->title(trans('plugins/seo-boost::seo-boost.url'))
                    ->alignStart()
                    ->renderUsing(function (FormattedColumn $column) {
                        return '<code class="small">' . e($column->getItem()->url) . '</code>';
                    }),
                FormattedColumn::make('status_code')
                    ->title(trans('plugins/seo-boost::seo-boost.status_code'))
                    ->width(100)
                    ->renderUsing(function (FormattedColumn $column) {
                        $log = $column->getItem();

                        $badgeClass = match (true) {
                            $log->is_success => 'success',
                            $log->status_code > 0 => 'danger',
                            default => 'secondary',
                        };

                        return '<span class="badge bg-' . $badgeClass . '">' . ($log->status_code ?: '—') . '</span>';
                    }),
                FormattedColumn::make('message')
                    ->title(trans('plugins/seo-boost::seo-boost.message'))
                    ->alignStart()
                    ->renderUsing(function (FormattedColumn $column) {
                        return e((string) $column->getItem()->message);
                    }),
                YesNoColumn::make('is_manual')
                    ->title(trans('plugins/seo-boost::seo-boost.manual')),
                FormattedColumn::make('engine')
                    ->title(trans('plugins/seo-boost::seo-boost.engine'))
                    ->width(90)
                    ->renderUsing(function (FormattedColumn $column) {
                        $engine = $column->getItem()->engine ?: 'indexnow';
                        $class = $engine === 'google' ? 'danger' : 'primary';

                        return '<span class="badge bg-' . $class . '">' . ucfirst($engine) . '</span>';
                    }),
                FormattedColumn::make('action')
                    ->title(trans('plugins/seo-boost::seo-boost.action_label'))
                    ->width(90)
                    ->renderUsing(function (FormattedColumn $column) {
                        $action = $column->getItem()->action ?: 'update';

                        return $action === 'delete'
                            ? '<span class="text-danger">' . trans('plugins/seo-boost::seo-boost.action_delete') . '</span>'
                            : trans('plugins/seo-boost::seo-boost.action_update');
                    }),
                CreatedAtColumn::make('created_at')
                    ->title(trans('plugins/seo-boost::seo-boost.submitted_at')),
            ]);
    }
}
