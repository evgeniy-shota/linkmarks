@props(['id', 'required' => true])

@php
    $borderColors = [
        'enter' => 'border-sky-300/50',
        'error' => '',
        'success' => '',
    ];
@endphp

<div x-data=""
    class="box-border flex flex-col w-full justify-center items-center mb-2">
    <div class="flex justify-between items-center h-32 w-full mb-1">

        {{-- <div x-show="thumbnail" x-transition class="peer w-1/2 h-full">
            <x-html.thumbnail id="uploadFilePreview" src="" xSrc="thumbnail" />
        </div> --}}
        <div x-show="$store.fileInput.thumbnail" x-transition
            class="peer flex-none w-1/2 h-full">
            <x-html.thumbnail id="uploadFilePreview" src=""
                xSrc="$store.fileInput.thumbnail" />
        </div>

        <div x-ref="dropContainer"
            @@click="$refs.{{ $id }}.click()"
            @@drop="$store.fileInput.setFile($event, $refs.{{ $id }})"
            @@drop="$refs.dropContainer.classList.remove('{{ $borderColors['enter'] }}')"
            @@dragenter="(e)=>$store.fileInput.dragEnterHandler(e) && e.target.classList.add('{{ $borderColors['enter'] }}')"
            @@dragleave="(e)=>$store.fileInput.dragLeaveHandler(e) && e.target.classList.remove('{{ $borderColors['enter'] }}')"
            x-bind:class="$store.fileInput.thumbnail ? 'w-1/2' : 'w-full'"
            data-dropContainer
            class="flex justify-center items-center border-2 border-dashed rounded-sm border-gray-500 h-32 cursor-pointer transition delay-50 duration-150">
            <div class="p-1 text-sm flex flex-col items-center">
                <div>Click</div>
                <div>or drag and drop</div>
                <div class="mb-2">for upload image</div>
                <div x-show="$store.fileInput.error" class="text-red-400"
                    x-text="$store.fileInput.error"></div>
                <div x-show="$store.fileInput.thumbnail" x-transition
                    @@click.stop="$store.fileInput.clearData($refs.{{ $id }})"
                    class="btn btn-sm bg-gray-500 border-0 shadow-none">Clear
                </div>
            </div>
        </div>
    </div>

    <div class="w-full">
        <x-html.formcontrols.input-file
            x-on:change="$store.fileInput.fileChosen($event, (file)=>Alpine.store('bookmark').thumbnail=file)"
            :id="$id" :required="$required" accept="image/*" />
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('fileInput', {
            file: null,
            fileInput: null,
            thumbnail: null,
            dragEnterTargetIsDropContainer: null,
            error: null,

            fileChosen(event, callback) {
                if (!event.target.files.length) {
                    return
                }
                this.fileInput = event.target
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

            dragEnterHandler(e) {
                let element = e.target.hasAttribute(
                    'data-dropContainer');

                if (element) {
                    this.dragEnterTargetIsDropContainer = true
                    return true
                }

                this.dragEnterTargetIsDropContainer = false
                return false
            },

            dragLeaveHandler(e) {
                if (e.target.hasAttribute(
                        'data-dropContainer') && !this
                    .dragEnterTargetIsDropContainer) {

                    if (!e.target.closest(
                            '[data-dropContainer]')) {
                        return true
                    }

                    return false
                }

                return true
            },

            clearData(fileInput = this.fileInput) {
                console.log('clear')
                this.file = null;
                this.thumbnail = null;
                fileInput.value = null
            }
        })
    })

    // function fileInput(di = null) {
    //     return {
    //         file: null,
    //         thumbnail: null,
    //         dragEnterTargetIsDropContainer: null,
    //         error: null,

    //         fileChosen(event, callback) {
    //             if (!event.target.files.length) {
    //                 return
    //             }

    //             this.file = event.target.files[0]

    //             this.fileToDataUrl(this.file, src => {
    //                 this.thumbnail = src;
    //             })

    //             callback(this.file)
    //         },

    //         fileToDataUrl(file, callback) {

    //             let reader = new FileReader()
    //             reader.readAsDataURL(file)
    //             reader.onload = e => callback(e.target.result)
    //         },

    //         setFile(event, formInput) {
    //             let file = event.dataTransfer?.files[0];

    //             if (!file) {
    //                 return
    //             }

    //             formInput.files = event.dataTransfer.files
    //             formInput.dispatchEvent(new Event("change"));
    //         },

    //         dragEnterHandler(e) {
    //             let element = e.target.hasAttribute('data-dropContainer');

    //             if (element) {
    //                 this.dragEnterTargetIsDropContainer = true
    //                 return true
    //             }

    //             this.dragEnterTargetIsDropContainer = false
    //             return false
    //         },

    //         dragLeaveHandler(e) {
    //             if (e.target.hasAttribute('data-dropContainer') && !this
    //                 .dragEnterTargetIsDropContainer) {

    //                 if (!e.target.closest('[data-dropContainer]')) {
    //                     return true
    //                 }

    //                 return false
    //             }

    //             return true
    //         },

    //         clearData(fileInput) {
    //             console.log('clear')
    //             this.file = null;
    //             this.thumbnail = null;
    //             fileInput.value = null
    //         }
    //     }
    // }
</script>
