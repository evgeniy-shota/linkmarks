@props(['modalId'])

<div class="flex justify-center items-center">
    <div class="rounded-md bg-gray-600 px-4 py-3">

        <form x-data @@submit.prevent="submitFolderForm"
            action="" method="post">
            @csrf

             <div class="flex items-center gap-1">
                <div class="font-medium">Location:
                </div>
                <x-html.icons.four-square />
                <div x-text="$store.contexts.currentContext.name"></div>
            </div>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name"
                        type="text" placeholder="Youtube"
                        x-model="$store.context.name" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend
                        text="Enter folder name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- bookmark thumbnail preview --}}
            <template x-if="$store.context.thumbnail_id!==null">
                <div class="w-full">
                    <div class="text-base font-bold mb-1">Thumbnail</div>
                    <div
                        class="border-2 border-dashed rounded-sm border-gray-500 flex justify-between items-center h-32 w-full">
                        <div class="flex-none w-1/2">
                            <x-html.thumbnail id="contextThumbnailPreview"
                                src=""
                                xSrc="$store.context.thumbnail" />
                        </div>
                        <div class="w-1/2" class="flex-none">
                            <x-html.button
                                action="Alpine.store('context').clearThumbnail()">
                                Clear
                            </x-html.button>
                        </div>
                    </div>
                </div>

            </template>

            {{-- file input --}}
            <div class="font-bold">Thumbnail</div>
            <div x-show="$store.context.thumbnail_id===null">
                <x-html.formcontrols.input-file-drop-down id="thumbnail"
                    :required="false" />
            </div>

            <div class="flex justify-around items-center mt-2">
                <button type="reset"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Clear
                </button>

                <button type="submit"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Submit
                </button>
            </div>
        </form>

        <script>
            async function submitFolderForm() {
                let data = Alpine.store('context').getData();

                let result = await submitForm(
                    data,
                    '/context/',
                    (context) => Alpine.store('contexts').data.push(context))
                console.log(Alpine.store('contexts').data)
                if (result) {
                    {{ $modalId }}.close()
                }
            }
        </script>

    </div>
</div>
