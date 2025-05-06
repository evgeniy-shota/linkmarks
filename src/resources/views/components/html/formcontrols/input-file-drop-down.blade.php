@props(['id', 'required' => true])

<div x-data="fileInput()" class="box-border  flex flex-col w-full justify-center items-center mb-2">
    <div class="flex justify-between items-center h-32 w-full mb-1">

        <div x-show="thumbnail" x-transition class="peer">
            <x-html.thumbnail id="uploadFilePreview" src="" xSrc="thumbnail" />
        </div>

        <div @@click="$refs.thumbnail.click()"
            @@drop="setFile($event, $refs.{{ $id }})"
            @@dragenter="(e)=>e.target.classList.add('border-red-300')"
            @@dragleave="(e)=>e.target.classList.remove('border-red-300')"
            x-bind:class="thumbnail ? 'w-1/2' : 'w-full'"
            class="border-2 border-dashed rounded-sm border-gray-500 h-32 cursor-pointer transition delay-150 duration-150">
            <div class="p-1 text-sm flex flex-col items-center">
                <div>Click</div>
                <div>or drag and drop</div>
                <div class="mb-2">for upload image</div>
                <div x-show="thumbnail" x-transition
                    @@click.stop="clearData($refs.{{ $id }})"
                    class="btn btn-sm bg-gray-500 border-0 shadow-none">Clear</div>
            </div>
        </div>
    </div>

    <div class="w-full">
        <x-html.formcontrols.input-file
            x-on:change="fileChosen($event, (file)=>Alpine.store('bookmark').thumbnail=file)" :id="$id"
            :required="true" accept="image/*" />
    </div>
</div>

<script>
    function fileInput() {
        return {
            file: null,
            thumbnail: null,

            fileChosen(event, callback) {
                if (!event.target.files.length) {
                    return
                }

                this.file = event.target.files[0]

                this.fileToDataUrl(this.file, src => {
                    this.thumbnail = src;
                })

                callback(this.file)
            },

            fileToDataUrl(file, callback) {

                let reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onload = e => callback(e.target.result)
            },

            setFile(event, formInput) {
                let file = event.dataTransfer?.files[0];

                if (!file) {
                    return
                }

                formInput.files = event.dataTransfer.files
                formInput.dispatchEvent(new Event("change"));
            },

            clearData(fileInput) {
                console.log('clear')
                this.file = null;
                this.thumbnail = null;
                fileInput.value = null
            }
        }
    }
</script>
