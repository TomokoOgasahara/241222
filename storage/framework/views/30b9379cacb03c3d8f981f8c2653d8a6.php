<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function showCompanies(Request $request)
    {
        // 企業一覧を取得
        $companies = DB::table('compsdatabase')->select('comp_name')->distinct()->get();

        // 選択された企業のデータを取得
        $selectedCompany = null;
        $womensData = null;

        if ($request->has('comp_name')) {
            $selectedCompany = DB::table('compsdatabase')
                ->where('comp_name', $request->input('comp_name'))
                ->first();

            // womensdatabaseから関連データを取得
            $womensData = DB::table('womensdatabase')
                ->where('comp_name', $request->input('comp_name'))
                ->select('turnover_rate_rank')
                ->first();
        }

        return view('comps_database', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
            'womensData' => $womensData,
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企業データ表示</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>企業データ検索</h1>

    <!-- プルダウンフォーム -->
    <form method="GET" action="<?php echo e(url('/comps_database')); ?>">
        <label for="comp_name">企業名を選択してください:</label>
        <select name="comp_name" id="comp_name" required>
            <option value="">--選択してください--</option>
            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($company->comp_name); ?>" 
                        <?php echo e(request('comp_name') === $company->comp_name ? 'selected' : ''); ?>>
                    <?php echo e($company->comp_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit">検索</button>
    </form>

    <!-- 選択された企業のデータ表示 -->
    <?php if($selectedCompany): ?>
        <h2>選択した企業のデータ</h2>
        <p><strong>企業名:</strong> <?php echo e($selectedCompany->comp_name); ?></p>
        <p><strong>平均年齢:</strong> <?php echo e($selectedCompany->av_age); ?>歳</p>
        <p><strong>平均給与:</strong> <?php echo e($selectedCompany->av_salary); ?>万円</p>
        <p><strong>売上:</strong> <?php echo e($selectedCompany->sales); ?>万円</p>
        <p><strong>利益:</strong> <?php echo e($selectedCompany->profit); ?>万円</p>
        <p><strong>純利益:</strong> <?php echo e($selectedCompany->net_profit); ?>万円</p>

        <!-- womensdatabaseのデータを表示 -->
        <?php if($womensData): ?>
            <p><strong>離脱率ランキング/27位中:</strong> <?php echo e($womensData->turnover_rate_rank); ?></p>
            <p><strong>平均勤続年数ランキング/27位中:</strong> <?php echo e($womensData->avg_tenure_rank); ?></p>
            <p><strong>男女の賃金差ランキング/27位中:</strong> <?php echo e($womensData->wage_gap_rank); ?></p>
            
            <!-- Chart.js グラフ -->
            <canvas id="genderChart" width="100" height="100"></canvas>
            <canvas id="tenureChart" width="100" height="100"></canvas>
            <canvas id="wagegapChart" width="100" height="100"></canvas>

            <script>
                const ctx = document.getElementById('genderChart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['労働者', '監督者', '管理職', '役員'],
                        datasets: [{
                            label: '女性比率 (%)',
                            data: [
                                <?php echo e($womensData->female_worker_ratio); ?>,
                                <?php echo e($womensData->female_supervisor_ratio); ?>,
                                <?php echo e($womensData->female_manager_ratio); ?>,
                                <?php echo e($womensData->female_executive_ratio); ?>

                            ],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(54, 162, 235, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });

                const tenureCtx = document.getElementById('tenureChart').getContext('2d');
                new Chart(tenureCtx, {
                    type: 'bar',
                    data: {
                        labels: ['男性', '女性'],
                        datasets: [{
                            label: '平均勤続年数 (年)',
                            data: [
                                <?php echo e($womensData->avg_tenure_men); ?>,
                                <?php echo e($womensData->avg_tenure_women); ?>

                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                stepSize: 1,
                                title: {
                                    display: true,
                                    text: '年数'
                                }
                            }
                        }
                    }
                });

                const wagegapCtx = document.getElementById('wagegapChart').getContext('2d');
                new Chart(wagegapChart, {
                    type: 'bar',
                    data: {
                        labels: ['男性', '女性'],
                        datasets: [{
                            label: '男女の賃金差 (％)',
                            data: [
                                <?php echo e($womensData->wage_gap_by_men); ?>,
                                <?php echo e($womensData->wage_gap_by_gender); ?>

                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                stepSize: 1,
                                title: {
                                    display: true,
                                    text: '賃金差'
                                }
                            }
                        }
                    }
                });

            </script>

<p><strong>平均残業時間:</strong> <?php echo e($womensData->avg_overtime_hours); ?>時間</p>
<p><strong>有給休暇取得率:</strong> <?php echo e($womensData->paid_leave_usage_rate); ?>％</p>
        <?php else: ?>
            <p>離職率ランキングのデータは見つかりませんでした。</p>
        <?php endif; ?>
    <?php elseif(request()->has('comp_name')): ?>
        <p>選択した企業のデータは見つかりませんでした。</p>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/gs-laravel/resources/views/comps_database.blade.php ENDPATH**/ ?>