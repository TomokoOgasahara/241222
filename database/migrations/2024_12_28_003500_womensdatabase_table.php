<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('womensdatabase', function (Blueprint $table) {
            $table->id();

            $table->text('comp_name')->nullable()->comment('企業名');
            $table->string('comp_no')->nullable()->comment('法人番号');
            $table->string('turnover_rate_rank')->nullable()->comment('離脱率ランキング');
            $table->string('avg_tenure_rank')->nullable()->comment('平均勤続年数ランキング');
            $table->string('wage_gap_rank')->nullable()->comment('賃金差ランキング');
            $table->string('female_worker_ratio')->nullable()->comment('労働者に占める女性割合');
            $table->string('female_supervisor_ratio')->nullable()->comment('係長級に占める女性割合');
            $table->string('female_manager_ratio')->nullable()->comment(' 課長級に占める女性割合');
            $table->string('female_executive_ratio')->nullable()->comment('役員に占める女性割合');
            $table->string('avg_tenure_men')->nullable()->comment('男女の平均勤続年数_男性');
            $table->string('avg_tenure_women')->nullable()->comment('男女の平均勤続年数_女性');
            $table->string('wage_gap_by_men')->nullable()->comment('男女の賃金差_男性');
            $table->string('wage_gap_by_gender')->nullable()->comment('男女の賃金差_女性');
            $table->string('avg_overtime_hours')->nullable()->comment('平均残業時間');
            $table->string('paid_leave_usage_rate')->nullable()->comment('有給休暇取得率');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
