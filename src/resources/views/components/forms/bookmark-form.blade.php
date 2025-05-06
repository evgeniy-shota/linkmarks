<div class="flex justify-center items-center">
    <div class="rounded-md bg-gray-600 px-4 py-3">

        <form x-data @@submit.prevent="submitForm" action="" method="post">
            @csrf

            {{-- link input --}}
            <x-html.formcontrols.fieldset title='Link'>
                <x-slot:field>
                    <x-html.formcontrols.input required id="link" type="text" placeholder="www.youtube.com"
                        x-model="$store.bookmark.link" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend text="Enter link" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name" type="text" placeholder="Youtube"
                        x-model="$store.bookmark.name" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend text="Enter bookmark name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- file input --}}
            <x-html.formcontrols.input-file-drop-down id="thumbnail" />

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
            async function submitForm() {
                console.log('try submit');
                console.log(Alpine.store('bookmark').getData())
                let response = null;
                try {
                    response = await axios.post(
                        '/bookmarks/',
                        Alpine.store('bookmark').getData(), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    );
                } catch (error) {
                    console.warn(error);
                }

                if (response) {
                    console.log(response.data)
                }
            }

            function uploadImage() {}

            function getThumbnail() {

            }
        </script>

    </div>
</div>
