{{-- resources/views/filament/resources/systems/widgets/stats.blade.php --}}

<x-filament-widgets::widget>
    <div
        class="fi-sc fi-sc-has-gap fi-grid fi-section-content"
        style="
            --cols-default: repeat(2, minmax(0, 1fr));
            --cols-nclg: repeat(2, minmax(0, 1fr));
            --cols-cxl: repeat(2, minmax(0, 1fr));
        "
    >
        @foreach ($this->getCachedStats() as $stat)
            <div class="fi-grid-col" style="--col-span-default: span 1 / span 1;">
                <div class="fi-sc-component">
                    <div class="fi-wi-stats-overview-stat">
                        <div class="fi-wi-stats-overview-stat-content">
                            <div class="fi-wi-stats-overview-stat-label-ctn">
                                <span class="fi-wi-stats-overview-stat-label">
                                    {{ $stat->getLabel() }}
                                </span>
                            </div>

                            <div class="fi-wi-stats-overview-stat-value">
                                {{ $stat->getValue() }}
                            </div>

                            @if ($stat->getDescription())
                                <div class="fi-wi-stats-overview-stat-description">
                                    <span>{{ $stat->getDescription() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
