<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class OrdersStatisticsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $start;
    protected $end;
    protected $filterType;
    protected $dateLabel;

    public function __construct($start, $end, $filterType, $dateLabel)
    {
        $this->start = $start;
        $this->end = $end;
        $this->filterType = $filterType;
        $this->dateLabel = $dateLabel;
    }

    public function collection(): Collection
    {
        $data = new Collection();

        // Tổng doanh số
        $expectedRevenue = Order::whereHas('orderStatuses', function ($query) {
            $query->whereIn('order_status_id', [1, 2, 3, 4])
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->sum('total_amount');

        $actualRevenue = Order::whereHas('orderStatuses', function ($query) {
            $query->where('order_status_id', 6)
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->sum('total_amount');

        $canceledRevenue = Order::whereHas('orderStatuses', function ($query) {
            $query->where('order_status_id', 7)
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->sum('total_amount');

        $data->push([
            'type' => 'Tổng doanh số',
            'label' => 'Doanh số dự tính',
            'value' => $expectedRevenue,
        ]);
        $data->push([
            'type' => 'Tổng doanh số',
            'label' => 'Doanh số thực tế',
            'value' => $actualRevenue,
        ]);
        $data->push([
            'type' => 'Tổng doanh số',
            'label' => 'Doanh số bị hủy',
            'value' => $canceledRevenue,
        ]);

        // Trạng thái đơn hàng
        $pendingOrdersCount = Order::whereHas('orderStatuses', function ($query) {
            $query->where('order_status_id', 1)
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->count();

        $completedOrdersCount = Order::whereHas('orderStatuses', function ($query) {
            $query->where('order_status_id', 6)
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->count();

        $canceledOrdersCount = Order::whereHas('orderStatuses', function ($query) {
            $query->where('order_status_id', 7)
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->count();

        $data->push([
            'type' => 'Trạng thái đơn hàng',
            'label' => 'Đơn chờ xác nhận',
            'value' => $pendingOrdersCount,
        ]);
        $data->push([
            'type' => 'Trạng thái đơn hàng',
            'label' => 'Đơn hoàn thành',
            'value' => $completedOrdersCount,
        ]);
        $data->push([
            'type' => 'Trạng thái đơn hàng',
            'label' => 'Đơn bị hủy',
            'value' => $canceledOrdersCount,
        ]);

        // Top 10 người dùng
        $topUsers = User::whereHas('orders.orderStatuses', function ($query) {
            $query->whereIn('order_status_id', [1, 2])
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->with(['orders' => function ($query) {
            $query->whereHas('orderStatuses', function ($q) {
                $q->whereIn('order_status_id', [1, 2])
                  ->whereBetween('created_at', [$this->start, $this->end]);
            });
        }])->select('users.*')
          ->withCount(['orders as orders_count' => function ($query) {
              $query->whereHas('orderStatuses', function ($q) {
                  $q->whereIn('order_status_id', [1, 2])
                    ->whereBetween('created_at', [$this->start, $this->end]);
              });
          }])->orderBy('orders_count', 'desc')
          ->take(10)
          ->get()
          ->map(function ($user) {
              $user->total_spent = $user->orders->sum('total_amount');
              return $user;
          });

        foreach ($topUsers as $user) {
            $data->push([
                'type' => 'Top 10 người dùng',
                'label' => $user->fullname,
                'value' => $user->total_spent,
            ]);
        }

        // Top 10 sản phẩm
        $topProducts = Product::whereHas('items.order.orderStatuses', function ($query) {
            $query->whereIn('order_status_id', [1, 2])
                  ->whereBetween('created_at', [$this->start, $this->end]);
        })->with(['items' => function ($query) {
            $query->whereHas('order.orderStatuses', function ($q) {
                $q->whereIn('order_status_id', [1, 2])
                  ->whereBetween('created_at', [$this->start, $this->end]);
            });
        }])->select('products.*')
          ->withCount(['items as items_sold' => function ($query) {
              $query->whereHas('order.orderStatuses', function ($q) {
                  $q->whereIn('order_status_id', [1, 2])
                    ->whereBetween('created_at', [$this->start, $this->end]);
              });
          }])->orderBy('items_sold', 'desc')
          ->take(10)
          ->get();

        foreach ($topProducts as $product) {
            $data->push([
                'type' => 'Top 10 sản phẩm',
                'label' => $product->name,
                'value' => $product->items_sold,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Loại dữ liệu',
            'Nhãn',
            'Giá trị',
        ];
    }

    public function map($row): array
    {
        return [
            $row['type'],
            $row['label'],
            $row['value'],
        ];
    }

    public function title(): string
    {
        return 'Thống kê đơn hàng - ' . $this->dateLabel;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô và thêm tiêu đề
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', 'Thống kê đơn hàng - ' . $this->dateLabel);
                
                // Định dạng tiêu đề
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Dịch chuyển dữ liệu xuống dưới tiêu đề
                $sheet->getStyle('A2:C2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                
                // Điều chỉnh chiều cao dòng tiêu đề
                $sheet->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}