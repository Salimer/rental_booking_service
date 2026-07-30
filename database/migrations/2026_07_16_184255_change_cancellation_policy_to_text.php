<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new columns to org_settings
        Schema::table('org_settings', function (Blueprint $table) {
            $table->text('cancellation_policy_en')->nullable()->after('org_id');
            $table->text('cancellation_policy_ar')->nullable()->after('cancellation_policy_en');
        });

        // 2. Add new columns to property_settings
        Schema::table('property_settings', function (Blueprint $table) {
            $table->text('cancellation_policy_en')->nullable()->after('property_id');
            $table->text('cancellation_policy_ar')->nullable()->after('cancellation_policy_en');
        });

        // 3. Migrate existing data for org_settings
        $orgSettings = DB::table('org_settings')->get();
        foreach ($orgSettings as $setting) {
            $policy = $setting->cancellation_policy ?? 'moderate';
            $texts = $this->getPolicyTexts($policy);
            DB::table('org_settings')
                ->where('id', $setting->id)
                ->update([
                    'cancellation_policy_en' => $texts['en'],
                    'cancellation_policy_ar' => $texts['ar'],
                ]);
        }

        // 4. Migrate existing data for property_settings
        $propertySettings = DB::table('property_settings')->get();
        foreach ($propertySettings as $setting) {
            $policy = $setting->cancellation_policy ?? 'moderate';
            $texts = $this->getPolicyTexts($policy);
            DB::table('property_settings')
                ->where('id', $setting->id)
                ->update([
                    'cancellation_policy_en' => $texts['en'],
                    'cancellation_policy_ar' => $texts['ar'],
                ]);
        }

        // 5. Drop old columns
        Schema::table('org_settings', function (Blueprint $table) {
            $table->dropColumn('cancellation_policy');
        });

        Schema::table('property_settings', function (Blueprint $table) {
            $table->dropColumn('cancellation_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add back the enum columns
        Schema::table('org_settings', function (Blueprint $table) {
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict', 'non_refundable'])->default('moderate')->after('org_id');
        });

        Schema::table('property_settings', function (Blueprint $table) {
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict', 'non_refundable'])->default('moderate')->after('property_id');
        });

        // 2. Rollback data for org_settings
        $orgSettings = DB::table('org_settings')->get();
        foreach ($orgSettings as $setting) {
            $enumVal = $this->getPolicyEnum($setting->cancellation_policy_en);
            DB::table('org_settings')
                ->where('id', $setting->id)
                ->update([
                    'cancellation_policy' => $enumVal,
                ]);
        }

        // 3. Rollback data for property_settings
        $propertySettings = DB::table('property_settings')->get();
        foreach ($propertySettings as $setting) {
            $enumVal = $this->getPolicyEnum($setting->cancellation_policy_en);
            DB::table('property_settings')
                ->where('id', $setting->id)
                ->update([
                    'cancellation_policy' => $enumVal,
                ]);
        }

        // 4. Drop new columns
        Schema::table('org_settings', function (Blueprint $table) {
            $table->dropColumn(['cancellation_policy_en', 'cancellation_policy_ar']);
        });

        Schema::table('property_settings', function (Blueprint $table) {
            $table->dropColumn(['cancellation_policy_en', 'cancellation_policy_ar']);
        });
    }

    /**
     * Get textual cancellation policies based on old enum values.
     */
    private function getPolicyTexts(string $policy): array
    {
        switch ($policy) {
            case 'flexible':
                return [
                    'en' => 'Flexible (Free cancellation any time)',
                    'ar' => 'إلغاء مرن (إلغاء مجاني في أي وقت)',
                ];
            case 'strict':
                return [
                    'en' => 'Strict (No refunds)',
                    'ar' => 'إلغاء صارم (لا يوجد استرجاع)',
                ];
            case 'non_refundable':
                return [
                    'en' => 'Non-refundable (No refunds at all)',
                    'ar' => 'غير مسترد (لا يوجد استرجاع نهائياً)',
                ];
            case 'moderate':
            default:
                return [
                    'en' => 'Moderate (Cancel up to 24h prior)',
                    'ar' => 'إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)',
                ];
        }
    }

    /**
     * Try to map text cancellation policy back to enum.
     */
    private function getPolicyEnum(?string $text): string
    {
        if (! $text) {
            return 'moderate';
        }

        $textLower = strtolower($text);
        if (str_contains($textLower, 'flexible')) {
            return 'flexible';
        }
        if (str_contains($textLower, 'strict')) {
            return 'strict';
        }
        if (str_contains($textLower, 'non-refundable') || str_contains($textLower, 'non_refundable') || str_contains($textLower, 'no refunds at all')) {
            return 'non_refundable';
        }

        return 'moderate';
    }
};
