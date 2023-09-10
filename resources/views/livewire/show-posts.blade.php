<div style="height: 20rem;">
    

<div class="row">
    <div class="col" style="height: 20rem;">
        <livewire:livewire-column-chart
        :column-chart-model="$columnChartModel"
    />
    </div>
    <div class="col" style="height: 20rem;">
        <livewire:livewire-pie-chart
            key="{{ $pieChartModel->reactiveKey() }}"
            :pie-chart-model="$pieChartModel"
        />
    </div>
</div>
 
</div>
