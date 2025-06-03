@props(['modalId', 'canDeleted' => false])


<div class="flex justify-center items-center w-full">
    <div class="rounded-md bg-gray-600 p-1 w-full">

        <form x-data @@submit.prevent="submitTagForm"
            action="" method="post">
            @csrf

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name"
                        type="text" x-model="$store.tag.name"
                        :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend
                        text="Enter tag name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Description' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input id="description" type="text"
                        x-model="$store.tag.description" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend
                        text="Enter tag description" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            <x-html.formcontrols.button-group deleteAction="deleteTag"
                canDeleted="{{ $canDeleted }}" />

        </form>

        <script>
            async function submitTagForm() {
                let data = Alpine.store('tag').getData();

                if (Alpine.store('tag').id === null) {
                    createTag(data)
                } else {
                    updateTag(data)
                }
            }

            async function createTag(data) {

                let result = await submitForm(
                    data,
                    '/tags/',
                    (tag) => Alpine.store('tags').tags.push(tag))
                console.log(Alpine.store('tags').tags)

                if (result) {
                    {{ $modalId }}.close()
                    clearTagStore()
                    Alpine.store('alerts').addAlert('Tag added successfully',
                        'success')
                }
            }

            async function updateTag(data) {
                console.log(data);

                let result = await updateRequest(
                    '/tags/' + data.id,
                    data, )
                // (tag) => Alpine.store('tags').tags.push(tag)

                if (result) {
                    Alpine.store('tags').tags[data.indexInTags] = result
                    {{ $modalId }}.close()
                    clearTagStore()
                    Alpine.store('alerts').addAlert('Tag updated successfully',
                        'success')
                } else {
                    Alpine.store('alerts').addAlert('Failed! Tag not updated!',
                        'warning')
                }
            }

            async function deleteTag() {

                let result = await deleteRequest(
                    '/tags/',
                    Alpine.store('tag').id);

                if (result) {
                    Alpine.store('tags').tags.splice(
                        Alpine.store('tag').indexInTags,
                        1);
                    {{ $modalId }}.close()
                    clearTagStore()
                    Alpine.store('alerts').addAlert('Tag removed!', 'success');
                } else {
                    Alpine.store('alerts').addAlert('Failed! Tag not removed!',
                        'warning')
                }
            }

            function clearForm(fileInput) {
                console.log(fileInput);
                Alpine.store('tag').clear()
            }
        </script>

    </div>
</div>
