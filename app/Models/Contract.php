<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'broker_id',
        'stock_committed',
        'price',
        'broker_commission_percentage',
        'destination_bank',
        'destination_port',
        'contract_variations',
        'status',
        'contract_number',
        'contract_date',
        'product_type',
        'booking_number',
        'vessel_name',
        'etd_date',
        'etd_week',
        'eta_date',
        'eta_week',
        'container_number',
        'transit_weeks',
        'freight_amount',
        'payment_status',
        'consignee_name',
        'consignee_address',
        'consignee_chinese_address',
        'consignee_tax_id',
        'consignee_phone',
        'notify_name',
        'notify_address',
        'notify_chinese_address',
        'notify_tax_id',
        'notify_phone',
        'contact_person_1_name',
        'contact_person_1_phone',
        'contact_person_2_name',
        'contact_person_2_phone',
        'contact_email',
        'seller_name',
        'seller_address',
        'seller_phone',
        'product_description',
        'quality_specification',
        'crop_year',
        'packing',
        'label_info',
        'incoterm',
        'payment_terms',
        'required_documents',
        'customer_reference',
        'port_of_charge',
        'maturity_date',
        'transportation_details',
        'shipment_schedule',
        'seller_tax_id',
        'seller_bank_name',
        'seller_bank_account_number',
        'seller_bank_swift',
        'seller_bank_address',
        'payment_type',
        'contract_clause',
        'total_amount',
        'unit_price_per_kg',
        'seller_date',
        'contract_ref',
        'payment_per_container',
        'humidity',
        'total_defects',
        'beneficiary',
        'beneficiary_bank_account',
        'beneficiary_account_number_swift',
        'commercial_details',
        'product_description_english',
        'quality_specification_english',
        'packing_english',
        'seller_address_english',
        'consignee_address_english',
        'notify_address_english',
        'payment_terms_english',
        'required_documents_english',
        'transportation_details_english',
        'shipment_schedule_english',
        'contract_clause_english',
        'commercial_details_english',
    ];

    protected $casts = [
        'stock_committed' => 'decimal:2',
        'price' => 'decimal:2',
        'broker_commission_percentage' => 'decimal:2',
        'contract_variations' => 'array',
        'contract_date' => 'date',
        'seller_date' => 'date',
        'maturity_date' => 'date',
        'etd_date' => 'date',
        'eta_date' => 'date',
        'freight_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'unit_price_per_kg' => 'decimal:2',
        'payment_per_container' => 'decimal:2',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function modifications()
    {
        return $this->hasMany(ContractModification::class);
    }

    public function documents()
    {
        return $this->hasMany(ContractDocument::class);
    }

    public function brokerPayments()
    {
        return $this->hasMany(BrokerPayment::class);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'draft' => 'Borrador',
            'active' => 'Activo',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    // Calculate total value
    public function getTotalValueAttribute()
    {
        return $this->stock_committed * $this->price;
    }

    // Calculate broker commission
    public function getBrokerCommissionAttribute()
    {
        if (!$this->broker_commission_percentage) {
            return 0;
        }
        return ($this->total_value * $this->broker_commission_percentage) / 100;
    }
}
