<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Webkul\Support\Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Webkul\Tender\Filament\Resources\TenderOpportunityResource\Pages;
use Webkul\Tender\Models\TenderOpportunity;
use Webkul\Partner\Models\Partner;
use UnitEnum;
use BackedEnum;

class TenderOpportunityResource extends Resource
{
    protected static ?string $model = TenderOpportunity::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tender Lifecycle')
                    ->tabs([
                        Tab::make('Identify')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('series')
                                            ->label('Naming Series')
                                            ->disabled()
                                            ->placeholder('Generated after saving'),
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('client_id')
                                            ->relationship('client', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('sector')
                                            ->options([
                                                'public' => 'Public Sector',
                                                'private' => 'Private Sector',
                                                'ngo' => 'NGO/Non-Profit',
                                                'government' => 'Government',
                                            ]),
                                        Select::make('tender_type')
                                            ->options([
                                                'rfp' => 'RFP (Request for Proposal)',
                                                'rfq' => 'RFQ (Request for Quotation)',
                                                'eoi' => 'EOI (Expression of Interest)',
                                                'tender' => 'Open Tender',
                                            ]),
                                        Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'identified' => 'Identified',
                                                'qualification' => 'Qualification',
                                                'bid_preparation' => 'Bid Preparation',
                                                'submitted' => 'Submitted',
                                                'won' => 'Won',
                                                'lost' => 'Lost',
                                                'cancelled' => 'Cancelled',
                                            ])
                                            ->default('draft')
                                            ->required(),
                                        DateTimePicker::make('submission_deadline'),
                                        TextInput::make('value_estimated')
                                            ->numeric()
                                            ->prefix('$'),
                                        Select::make('manager_id')
                                            ->relationship('manager', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ]),
                                Section::make('External Reference & Source')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('tender_number')
                                                    ->label('Tender Number / Ref')
                                                    ->helperText('External tender reference'),
                                                TextInput::make('url')
                                                    ->label('Tender Link')
                                                    ->url(),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('ESG & Social Value')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('esg_impact_score')
                                                    ->label('ESG Impact Score')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ])
                                                    ->default('Medium'),
                                                TextInput::make('social_value_commitment')
                                                    ->label('Social Value Commitment ($)')
                                                    ->numeric()
                                                    ->prefix('$'),
                                                Textarea::make('environmental_initiatives')
                                                    ->label('Environmental Initiatives')
                                                    ->placeholder('Carbon reduction, sustainability commitments, etc.')
                                                    ->columnSpanFull(),
                                                Checkbox::make('governance_compliance')
                                                    ->label('Governance Compliance Checked'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Critical Dates')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                DatePicker::make('publication_date')
                                                    ->label('Publication Date'),
                                                DatePicker::make('clarification_deadline')
                                                    ->label('Clarification Deadline'),
                                                DatePicker::make('decision_date')
                                                    ->label('Decision Date'),
                                                DateTimePicker::make('site_visit_date')
                                                    ->label('Site Visit'),
                                                DateTimePicker::make('pre_bid_meeting_date')
                                                    ->label('Pre-Bid Meeting'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Document Purchase')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('document_price')
                                                    ->label('Purchase Price')
                                                    ->numeric()
                                                    ->prefix('$'),
                                                DatePicker::make('purchase_date')
                                                    ->label('Purchase Date'),
                                                TextInput::make('purchase_receipt_no')
                                                    ->label('Receipt No'),
                                                Select::make('doc_purchase_status')
                                                    ->label('Purchase Status')
                                                    ->options([
                                                        'Pending Assignment' => 'Pending Assignment',
                                                        'Pending Request' => 'Pending Request',
                                                        'Finance Review' => 'Finance Review',
                                                        'Funds Released' => 'Funds Released',
                                                        'Completed' => 'Completed',
                                                    ])
                                                    ->default('Pending Assignment'),
                                                Select::make('tender_purchaser_id')
                                                    ->label('Tender Purchaser')
                                                    ->relationship('tenderPurchaser', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                                TextInput::make('doc_purchase_payment_entry')
                                                    ->label('Payment Entry')
                                                    ->disabled()
                                                    ->helperText('Auto-linked from finance'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Insurance Requirements')
                                    ->schema([
                                        Repeater::make('insuranceRequirements')
                                            ->relationship()
                                            ->label('Required Insurance Coverage')
                                            ->schema([
                                                Select::make('insurance_type')
                                                    ->label('Type')
                                                    ->options([
                                                        'Professional Indemnity' => 'Professional Indemnity',
                                                        'Public Liability' => 'Public Liability',
                                                        'Product Liability' => 'Product Liability',
                                                        'Employers Liability' => 'Employers Liability',
                                                        'Performance Bond' => 'Performance Bond',
                                                        'Other' => 'Other',
                                                    ])
                                                    ->default('Other')
                                                    ->required(),
                                                TextInput::make('coverage_amount')
                                                    ->label('Coverage Amount')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->required(),
                                                Checkbox::make('coverage_confirmed')
                                                    ->label('Confirmed'),
                                                Textarea::make('specific_requirements')
                                                    ->label('Specific Requirements')
                                                    ->placeholder('Details from tender document')
                                                    ->columnSpan(2),
                                                Textarea::make('insurer_details')
                                                    ->label('Insurer Details')
                                                    ->placeholder('Provider name and policy info')
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['insurance_type'] ?? null),
                                    ])
                                    ->collapsible(),
                                Section::make('Bill of Quantities (BOQ)')
                                    ->schema([
                                        Repeater::make('items')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('item_code')->label('Item Code'),
                                                TextInput::make('item_name')->label('Item Name'),
                                                TextInput::make('description')->required()->columnSpan(2),
                                                TextInput::make('uom')->label('UOM'),
                                                TextInput::make('qty')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(fn (Set $set, Get $get) => $set('total_cost', number_format((float) $get('qty') * (float) $get('rate'), 2, '.', ''))),
                                                TextInput::make('rate')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->live()
                                                    ->afterStateUpdated(fn (Set $set, Get $get) => $set('total_cost', number_format((float) $get('qty') * (float) $get('rate'), 2, '.', ''))),
                                                TextInput::make('total_cost')->disabled()->dehydrated()->prefix('$'),
                                                Textarea::make('notes')->columnSpanFull(),
                                            ])->columns(8),
                                    ]),
                            ]),
                        Tab::make('Qualify')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('bid_probability')
                                            ->label('Bid Probability Score (%)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100),
                                        Select::make('approver_id')
                                            ->label('Decision Approver')
                                            ->relationship('approver', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ]),
                                Repeater::make('rankings')
                                    ->label('Decision Matrix')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('criterion')
                                            ->required(),
                                        Select::make('score')
                                            ->options(array_combine(range(0, 10), range(0, 10)))
                                            ->required(),
                                        TextInput::make('weight')
                                            ->numeric()
                                            ->default(1.0)
                                            ->required(),
                                        Textarea::make('notes'),
                                    ])->columns(3),
                                Section::make('Detailed Bid Decision Factors')
                                    ->schema([
                                        Repeater::make('bidDecisionFactors')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('factor')
                                                    ->required(),
                                                TextInput::make('weight')
                                                    ->numeric()
                                                    ->default(1.0)
                                                    ->required(),
                                                TextInput::make('score')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(10)
                                                    ->required(),
                                                Textarea::make('comments'),
                                            ])->columns(4),
                                    ]),
                                Section::make('Strategic Qualification')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('strategic_alignment_score')
                                                    ->label('Strategic Alignment')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ])
                                                    ->default('Medium'),
                                                TextInput::make('historical_win_rate_with_client')
                                                    ->label('Client Win Rate (Historical %)')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->minValue(0)
                                                    ->maxValue(100),
                                                TextInput::make('incumbent_vendor')
                                                    ->label('Incumbent Vendor'),
                                                Textarea::make('opportunity_cost_assessment')
                                                    ->label('Opportunity Cost Assessment')
                                                    ->placeholder('What are we sacrificing to pursue this?')
                                                    ->columnSpanFull(),
                                                Textarea::make('decision_notes')
                                                    ->label('Decision Notes (Go/No-Go)')
                                                    ->placeholder('Detailed rationale for the bid/no-bid decision')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Budget & Costing')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('estimated_cost')
                                                    ->label('Estimated Cost')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $cost = (float) $get('estimated_cost');
                                                        $margin = (float) $get('margin_percentage');
                                                        $set('final_bid_price', $cost + ($cost * $margin / 100));
                                                    }),
                                                TextInput::make('margin_percentage')
                                                    ->label('Margin %')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $cost = (float) $get('estimated_cost');
                                                        $margin = (float) $get('margin_percentage');
                                                        $set('final_bid_price', $cost + ($cost * $margin / 100));
                                                    }),
                                                TextInput::make('final_bid_price')
                                                    ->label('Final Bid Price')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->readonly(),
                                                Select::make('budget_source')
                                                    ->options([
                                                        'CAPEX' => 'CAPEX',
                                                        'OPEX' => 'OPEX',
                                                        'Grant' => 'Grant',
                                                        'Mixed' => 'Mixed',
                                                        'Unknown' => 'Unknown',
                                                    ]),
                                                TextInput::make('project_duration_months')
                                                    ->label('Duration (Months)')
                                                    ->numeric(),
                                                TextInput::make('payment_terms')
                                                    ->label('Payment Terms')
                                                    ->placeholder('e.g. Net 30, Milestone-based'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Risk Assessment')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('technical_risk')
                                                    ->label('Technical Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ]),
                                                Select::make('commercial_risk')
                                                    ->label('Commercial Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ]),
                                                Select::make('financial_risk')
                                                    ->label('Financial Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ]),
                                                Select::make('scope_creep_risk')
                                                    ->label('Scope Creep Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ])
                                                    ->default('Low'),
                                                Select::make('resource_availability_risk')
                                                    ->label('Resource Availability Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ])
                                                    ->default('Low'),
                                                Select::make('reputational_risk')
                                                    ->label('Reputational Risk')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                    ])
                                                    ->default('Low'),
                                                Select::make('competition_level')
                                                    ->label('Competition Level')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Moderate' => 'Moderate',
                                                        'Intense' => 'Intense',
                                                        'Locked Spec' => 'Locked Spec (Competitor Favored)',
                                                    ]),
                                                Select::make('customer_relationship')
                                                    ->label('Customer Relationship')
                                                    ->options([
                                                        'New Client' => 'New Client',
                                                        'Existing - Good' => 'Existing - Good',
                                                        'Existing - Strained' => 'Existing - Strained',
                                                        'Blacklisted' => 'Blacklisted/Problematic',
                                                    ]),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Client Intelligence')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Textarea::make('relationship_history')
                                                    ->label('Relationship History')
                                                    ->placeholder('History with this client')
                                                    ->columnSpanFull(),
                                                Textarea::make('key_decision_makers')
                                                    ->label('Key Decision Makers')
                                                    ->placeholder('Who makes decisions? Names, titles, influence'),
                                                Textarea::make('past_performance')
                                                    ->label('Past Performance')
                                                    ->placeholder('Our past track record with this client'),
                                                Textarea::make('client_preferences')
                                                    ->label('Client Preferences')
                                                    ->placeholder('Known preferences and priorities')
                                                    ->columnSpanFull(),
                                                Checkbox::make('incumbent_advantage')
                                                    ->label('We Are Incumbent'),
                                                Textarea::make('political_landscape')
                                                    ->label('Political Landscape')
                                                    ->placeholder('Internal politics and stakeholders'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Qualification Criteria')
                                    ->schema([
                                        Repeater::make('qualificationCriteria')
                                            ->relationship()
                                            ->label('Tender Requirements Checklist')
                                            ->schema([
                                                TextInput::make('criterion')
                                                    ->label('Criterion')
                                                    ->required()
                                                    ->columnSpan(2),
                                                Select::make('type')
                                                    ->options([
                                                        'Mandatory' => 'Mandatory',
                                                        'Desirable' => 'Desirable',
                                                        'Scored' => 'Scored',
                                                    ])
                                                    ->default('Mandatory')
                                                    ->required(),
                                                Checkbox::make('met')
                                                    ->label('We Meet This'),
                                                Textarea::make('evidence')
                                                    ->label('Evidence')
                                                    ->placeholder('Supporting documentation/proof')
                                                    ->columnSpan(2),
                                                Textarea::make('gap_mitigation_plan')
                                                    ->label('Mitigation Plan')
                                                    ->placeholder('If not fully met, how do we mitigate?')
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(4)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['criterion'] ?? null),
                                    ])
                                    ->collapsible(),
                                Section::make('Competitors Tracking')
                                    ->schema([
                                        Repeater::make('tenderCompetitors')
                                            ->relationship()
                                            ->label('Expected Competition')
                                            ->schema([
                                                Select::make('competitor_id')
                                                    ->label('Competitor')
                                                    ->relationship('competitor', 'competitor_name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->createOptionForm([
                                                        TextInput::make('competitor_name')->required(),
                                                        TextInput::make('website'),
                                                    ]),
                                                TextInput::make('competitor_name')
                                                    ->label('Name (if not in master)')
                                                    ->placeholder('Quick entry if not in system'),
                                                Select::make('likelihood_to_bid')
                                                    ->label('Bid Likelihood')
                                                    ->options([
                                                        'Low' => 'Low',
                                                        'Medium' => 'Medium',
                                                        'High' => 'High',
                                                        'Confirmed' => 'Confirmed',
                                                    ])
                                                    ->default('Medium')
                                                    ->required(),
                                                Textarea::make('strengths')
                                                    ->label('Their Strengths')
                                                    ->placeholder('Why they might win')
                                                    ->columnSpan(2),
                                                Textarea::make('our_differentiation')
                                                    ->label('Our Differentiation')
                                                    ->placeholder('How we beat them')
                                                    ->columnSpan(2),
                                                TextInput::make('bid_price')
                                                    ->label('Est. Bid Price')
                                                    ->numeric()
                                                    ->prefix('$'),
                                                Toggle::make('is_winner')
                                                    ->label('Winner')
                                                    ->inline(false),
                                                Textarea::make('notes')
                                                    ->label('Analysis Notes')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['competitor_name'] ?? $state['competitor_id'] ?? null),
                                    ])
                                    ->collapsible(),
                            ]),
                        Tab::make('Plan')
                            ->icon('heroicon-o-map')
                            ->schema([
                                RichEditor::make('kick_off_notes'),
                                Textarea::make('win_themes')
                                    ->placeholder('What are our unique winning themes?'),
                                Textarea::make('narrative_strategy')
                                    ->placeholder('Key messaging and narrative approach'),
                                Checkbox::make('legal_review_checked')
                                    ->label('Legal Review Completed'),
                                Section::make('Capture Plan')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Textarea::make('customer_hot_buttons')
                                                    ->label('Customer Hot Buttons')
                                                    ->placeholder('Top 3 urgent issues for the client'),
                                                Textarea::make('key_success_factors')
                                                    ->label('Key Success Factors')
                                                    ->placeholder('What is critical to deliver?'),
                                                RichEditor::make('customer_pain_points')
                                                    ->label('Customer Pain Points')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                                RichEditor::make('solution_overview')
                                                    ->label('Solution Overview')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                                RichEditor::make('value_proposition')
                                                    ->label('Value Proposition')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                                RichEditor::make('executive_summary_themes')
                                                    ->label('Executive Summary Themes')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Data & Evidence Strategy')
                                    ->schema([
                                        Textarea::make('key_data_points')
                                            ->label('Key Data Points (Metrics)')
                                            ->placeholder('Stats/KPIs to prove our case'),
                                        Toggle::make('visualisation_required')
                                            ->label('Visualisation / Infographics Required')
                                            ->inline(false),
                                        RichEditor::make('narrative_strategy_details')
                                            ->label('Narrative Strategy')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    ])
                                    ->collapsible(),
                                Section::make('Compliance & Gov Watchpoints')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Toggle::make('price_lock_in_risk')->label('Price Lock-in Risk'),
                                                Select::make('supply_chain_volatility_risk')
                                                    ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'])
                                                    ->default('Low'),
                                                Toggle::make('contractual_clause_review')->label('Contractual Clauses Reviewed'),
                                                Toggle::make('payment_terms_negotiated')->label('Payment Terms Negotiated'),
                                                Toggle::make('team_capacity_check')->label('Team Capacity Checked'),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Team Assignment')
                                    ->schema([
                                        Repeater::make('teamMembers')
                                            ->relationship()
                                            ->label('Bid Team')
                                            ->schema([
                                                Select::make('user_id')
                                                    ->label('Team Member')
                                                    ->relationship('user', 'name')
                                                    ->searchable()
                                                    ->relationship('user', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->createOptionForm([
                                                        TextInput::make('name')->required(),
                                                        TextInput::make('email')->email()->required(),
                                                    ])
                                                    ->required(),
                                                DatePicker::make('assigned_date')
                                                    ->label('Date Assigned')
                                                    ->default(now()),
                                                Select::make('role')
                                                    ->options([
                                                        'Bid Manager' => 'Bid Manager',
                                                        'Technical Lead' => 'Technical Lead',
                                                        'Pricing Lead' => 'Pricing Lead',
                                                        'Proposal Writer' => 'Proposal Writer',
                                                        'Reviewer' => 'Reviewer',
                                                        'Subject Matter Expert' => 'Subject Matter Expert',
                                                        'Other' => 'Other',
                                                    ])
                                                    ->default('Other')
                                                    ->required(),
                                                Textarea::make('responsibilities')
                                                    ->label('Responsibilities')
                                                    ->placeholder('Key tasks and ownership areas')
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['role'] ?? null),
                                    ])
                                    ->collapsible(),
                            ]),
                        Tab::make('Proposal')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Repeater::make('proposalSections')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('title')->required()->columnSpan(3),
                                        Grid::make(4)
                                            ->schema([
                                                Select::make('status')
                                                    ->options([
                                                        'draft' => 'Draft',
                                                        'in_review' => 'In Review',
                                                        'final' => 'Final',
                                                    ]),
                                                Select::make('assigned_to_id')
                                                    ->relationship('assignedTo', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                                TextInput::make('weighting')
                                                    ->label('Weighting %')
                                                    ->numeric(),
                                                TextInput::make('word_count_limit')
                                                    ->label('Word Limit')
                                                    ->numeric(),
                                                Select::make('complexity')
                                                    ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'])
                                                    ->default('Medium'),
                                            ]),
                                        Textarea::make('key_message')->label('Key Message')->columnSpan(2),
                                        Textarea::make('customer_issues')->label('Customer Issues Address')->columnSpan(2),
                                        Textarea::make('evidence_required')->label('Evidence Required')->columnSpan(2),
                                        Textarea::make('instructions')->label('Specific Instructions')->columnSpanFull(),

                                        RichEditor::make('content')->columnSpanFull(),
                                    ])->columns(3),
                                Section::make('Submission Compliance Checklist')
                                    ->schema([
                                        Checkbox::make('mandatory_requirements_met')->label('Mandatory Requirements Met'),
                                        Checkbox::make('compliance_matrix_complete')->label('Compliance Matrix Complete'),
                                        Checkbox::make('formatting_check')->label('Formatting Check Passed'),
                                        Checkbox::make('submission_format_validated')->label('Submission Format Validated'),
                                    ])->columns(2),
                            ]),
                        Tab::make('Bid Bond')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('bond_type')
                                            ->options([
                                                'bank_guarantee' => 'Bank Guarantee',
                                                'insurance_bond' => 'Insurance Bond',
                                                'cash_deposit' => 'Cash Deposit',
                                            ]),
                                        TextInput::make('bond_amount')
                                            ->numeric()
                                            ->prefix('$'),
                                        DatePicker::make('bond_expiry_date'),
                                        TextInput::make('bid_security_request_link')
                                            ->url()
                                            ->placeholder('Link to digital security request'),
                                    ]),
                            ]),
                        Tab::make('Collaborate')
                            ->icon('heroicon-o-users')
                            ->schema([
                                Repeater::make('teamMembers')
                                    ->relationship()
                                    ->schema([
                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('role')
                                            ->placeholder('e.g. Technical Lead, Writer'),
                                    ])->columns(2),
                                Section::make('Clarification Questions')
                                    ->schema([
                                        Repeater::make('clarificationQuestions')
                                            ->relationship()
                                            ->schema([
                                                Textarea::make('question')
                                                    ->required()
                                                    ->columnSpan(2),
                                                DatePicker::make('date_asked')
                                                    ->default(now()),
                                                Textarea::make('answer')
                                                    ->columnSpan(2),
                                                Select::make('status')
                                                    ->options([
                                                        'pending' => 'Pending',
                                                        'answered' => 'Answered',
                                                        'closed' => 'Closed',
                                                    ])
                                                    ->default('pending'),
                                                Select::make('assigned_to_id')
                                                    ->relationship('assignedTo', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                            ])->columns(6),
                                    ]),
                            ]),
                        Tab::make('Tender Files')
                            ->icon('heroicon-o-paper-clip')
                            ->schema([
                                Section::make('Tender Documents')
                                    ->schema([
                                        FileUpload::make('full_tender_document')
                                            ->label('Full Tender Document (PDF)')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->downloadable()
                                            ->openable(),
                                        FileUpload::make('technical_proposal')
                                            ->label('Technical Proposal')
                                            ->downloadable()
                                            ->openable(),
                                        FileUpload::make('financial_proposal_doc')
                                            ->label('Financial Proposal')
                                            ->downloadable()
                                            ->openable(),
                                        FileUpload::make('payment_receipt_proof')
                                            ->label('Payment Receipt')
                                            ->image()
                                            ->previewable(true),
                                        FileUpload::make('source_evidence')
                                            ->label('Source Evidence')
                                            ->multiple()
                                            ->downloadable(),
                                    ]),
                                Section::make('Generated Documents')
                                    ->schema([
                                        Repeater::make('generatedDocuments')
                                            ->relationship()
                                            ->schema([
                                                Select::make('template_id')
                                                    ->relationship('template', 'template_name')
                                                    ->label('Template Used')
                                                    ->disabled(),
                                                FileUpload::make('file')
                                                    ->label('Generated File')
                                                    ->downloadable()
                                                    ->openable()
                                                    ->disk('public')
                                                    ->directory('tender-generated-docs'),
                                                DateTimePicker::make('generated_at')
                                                    ->label('Generated On')
                                                    ->disabled(),
                                                Select::make('generated_by_id')
                                                    ->relationship('generatedBy', 'name')
                                                    ->label('Generated By')
                                                    ->disabled(),
                                            ])
                                            ->columns(2)
                                            ->addable(false)
                                            ->deletable(false),
                                    ])
                                    ->collapsible(),
                                Section::make('Generated Documents')
                                    ->schema([
                                        Repeater::make('generatedDocuments')
                                            ->relationship()
                                            ->schema([
                                                Select::make('template_id')
                                                    ->relationship('template', 'template_name')
                                                    ->label('Template Used')
                                                    ->disabled(),
                                                FileUpload::make('file')
                                                    ->label('Generated File')
                                                    ->downloadable()
                                                    ->openable()
                                                    ->disk('public')
                                                    ->directory('tender-generated-docs'),
                                                DateTimePicker::make('generated_at')
                                                    ->label('Generated On')
                                                    ->disabled(),
                                                Select::make('generated_by_id')
                                                    ->relationship('generatedBy', 'name')
                                                    ->label('Generated By')
                                                    ->disabled(),
                                            ])
                                            ->columns(2)
                                            ->addable(false)
                                            ->deletable(false),
                                    ])
                                    ->collapsible(),
                            ]),
                        Tab::make('Outcome')
                            ->icon('heroicon-o-flag')
                            ->schema([
                                Section::make('Negotiation & Clarifications')
                                    ->schema([
                                        RichEditor::make('negotiation_notes')
                                            ->label('Negotiation Notes')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                        TextInput::make('final_contract_value')
                                            ->label('Final Contract Value')
                                            ->numeric()
                                            ->prefix('$'),
                                    ])
                                    ->collapsible(),
                                Section::make('Outcome & Debrief')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('loss_reason')
                                                    ->options([
                                                        'price' => 'High Price',
                                                        'technical' => 'Technical Non-compliance',
                                                        'commercial' => 'Commercial Terms',
                                                        'experience' => 'Lack of Experience',
                                                    ]),
                                                TextInput::make('winning_bid_price')
                                                    ->numeric()
                                                    ->prefix('$'),
                                                TextInput::make('price_difference')
                                                    ->label('Price Difference')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->helperText('Gap between our bid and winner'),
                                                Select::make('client_feedback_score')
                                                    ->label('Client Feedback Score (1-10)')
                                                    ->options(array_combine(range(1, 10), range(1, 10))),
                                                Textarea::make('main_weakness_identified')
                                                    ->label('Main Weakness Identified')
                                                    ->columnSpanFull(),
                                                RichEditor::make('debrief_notes')
                                                    ->label('Debrief Notes')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                                    ->columnSpanFull(),
                                                Textarea::make('lessons_learned')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Reporting Metrics')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('revenue_potential')
                                                    ->label('Revenue Potential')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $revenue = (float) $get('revenue_potential');
                                                        $prob = (float) $get('bid_probability');
                                                        $set('weighted_revenue', $revenue * ($prob / 100));
                                                    }),
                                                TextInput::make('weighted_revenue')
                                                    ->label('Weighted Revenue')
                                                    ->numeric()
                                                    ->prefix('$')
                                                    ->readonly(),
                                                Select::make('forecast_quarter')
                                                    ->options([
                                                        'Q1' => 'Q1',
                                                        'Q2' => 'Q2',
                                                        'Q3' => 'Q3',
                                                        'Q4' => 'Q4',
                                                    ]),
                                            ]),
                                    ])
                                    ->collapsible(),
                                Section::make('Post-Bid Presentation')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('shortlisted_for_presentation')
                                                    ->label('Shortlisted for Presentation')
                                                    ->inline(false),
                                                DateTimePicker::make('presentation_date'),
                                                Select::make('presentation_leader_id')
                                                    ->relationship('presentationLeader', 'name') // Need relationship in model
                                                    ->searchable()
                                                    ->preload(),
                                                Textarea::make('presentation_feedback')
                                                    ->label('Feedback Received')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'identified' => 'info',
                        'qualification' => 'warning',
                        'bid_preparation' => 'primary',
                        'submitted' => 'success',
                        'won' => 'success',
                        'lost' => 'danger',
                        'cancelled' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('submission_deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value_estimated')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('go_no_go_score')
                    ->label('Score')
                    ->numeric(2),
                Tables\Columns\TextColumn::make('recommendation')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bid (Go)' => 'success',
                        'No-Go' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'identified' => 'Identified',
                        'qualification' => 'Qualification',
                        'bid_preparation' => 'Bid Preparation',
                        'submitted' => 'Submitted',
                        'won' => 'Won',
                        'lost' => 'Lost',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('submit_bid')
                        ->label('Submit Bid')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (TenderOpportunity $record) => in_array($record->status, ['draft', 'identified', 'qualification', 'bid_preparation']))
                        ->action(fn (TenderOpportunity $record) => $record->update(['status' => 'submitted'])),
                    Action::make('mark_won')
                        ->label('Mark as Won')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (TenderOpportunity $record) => $record->status === 'submitted')
                        ->action(fn (TenderOpportunity $record) => $record->update(['status' => 'won'])),
                    Action::make('mark_lost')
                        ->label('Mark as Lost')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (TenderOpportunity $record) => $record->status === 'submitted')
                        ->action(fn (TenderOpportunity $record) => $record->update(['status' => 'lost'])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenderOpportunities::route('/'),
            'create' => Pages\CreateTenderOpportunity::route('/create'),
            'view' => Pages\ViewTenderOpportunity::route('/{record}'),
            'edit' => Pages\EditTenderOpportunity::route('/{record}/edit'),
        ];
    }
}
