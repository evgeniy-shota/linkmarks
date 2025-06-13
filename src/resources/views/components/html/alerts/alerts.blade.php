@props(['ref', 'xData' => ''])
{{-- absolute --}}
<div x-data={timer:null} class="fixed bottom-5 right-0 sm:max-w-2/5">
    <template x-for="(alert, index) in $store.alerts.alertList">
        <div id="{{ $ref }}+index" x-refs="{{ $ref }}+index"
            role="alert"
            x-bind:class="'z-11 mb-2 bg-gray-600 alert ' + classesFromType(alert.type)"
            x-init="timer = setTimeout(function() { $store.alerts.delAlert(alert.index) }, 5000)">
            <x-html.icons.info />
            <span x-text="alert.message">
            </span>
            <div>
                <x-html.button-out-gray
                    action="$store.alerts.delAlert(alert.index)" class="btn-sm">
                    <x-html.icons.x-lg size="16" />
                </x-html.button-out-gray>
            </div>
        </div>
    </template>
</div>

<script>
    function classesFromType(type) {
        if (type === 'success') {
            return 'border-green-400 text-green-400'
        }

        if (type === 'info') {
            return 'border-sky-400 text-sky-400'
        }

        if (type === 'warning') {
            return 'border-yellow-400 text-yellow-400'
        }

        if (type === 'danger') {
            return 'border-rose-400 text-rose-400'
        }

        return 'border-gray-200 text-gray-200'
    }
</script>
