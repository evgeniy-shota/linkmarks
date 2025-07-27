<div x-data="{ selectedTab: 'en' }" class="max-h-full overflow-hidden">

    <div class="flex justify-start align-center mb-1">
        <div x-on:click="selectedTab='en'"
            x-bind:class="selectedTab == 'en' ? 'bg-gray-600 text-sky-400' : 'bg-gray-500'"
            class="w-[2.5rem] text-center py-1 px-2 rounded-s-sm border-1 border-gray-800 cursor-pointer">en</div>
        <div x-on:click="selectedTab='ru'"
            x-bind:class="selectedTab == 'ru' ? 'bg-gray-600 text-sky-400' : 'bg-gray-500'"
            class="w-[2.5rem] text-center py-1 px-2 rounded-e-sm border-1 border-gray-800 cursor-pointer">ru</div>
    </div>

    {{-- EN --}}
    <template x-if="selectedTab=='en'">
        <div class="rounded-sm bg-gray-700 h-[65vh] p-2 border-gray-800 overflow-hidden">
            <div class="h-full overflow-auto ">
                <div class="mb-1">
                    <div class="font-semibold">
                        1. Description of the service
                    </div>
                    <div class="text-justify px-2">
                        This website provides users with the ability to save links (bookmarks) to additional resources.
                        The site administration does not publish, recommend or distribute content included via links.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        2. User's responsibility
                    </div>
                    <div class="text-justify px-2">
                        The user is fully responsible for the content of the bookmarks he/she saves. The user undertakes
                        not to save links that violate the law, the rights of third parties or contain prohibited
                        content.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        3. Limitation of Liability
                    </div>
                    <div class="text-justify px-2">
                        The site administration is not responsible for the content of third-party resources to which
                        bookmarks saved by users lead. The site does not moderate, check or distribute these links.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        4. Removing content
                    </div>
                    <div class="text-justify px-2">
                        In case of receiving justified complaints from third parties or government agencies, the site
                        administration reserves the right to delete the corresponding bookmarks without prior notice to
                        the user.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        5. Transfer of personal data
                    </div>
                    <div class="text-justify px-2">
                        The site administration undertakes to maintain the confidentiality of users' personal data and
                        not to transfer them to third parties, except in cases stipulated by law. In particular, data
                        may be transferred to competent government agencies upon their official request, in accordance
                        with the current legislation of the Republic of Belarus.
                    </div>
                </div>

                <div class="hidden mb-1">
                    <div class="font-semibold">
                        6. Jurisdiction
                    </div>
                    <div class="text-justify px-2 ">
                        This agreement is governed by the laws of the Republic of Belarus. All disputes are subject to
                        consideration in the relevant judicial bodies.
                    </div>
                </div>

            </div>
        </div>
    </template>

    {{-- RU --}}
    <template x-if="selectedTab=='ru'">
        <div class="rounded-sm bg-gray-700 h-[65vh] p-2 border-gray-800 overflow-hidden">
            <div class="h-full overflow-auto ">
                <div class="mb-1">
                    <div class="font-semibold">
                        1. Описание сервиса
                    </div>
                    <div class="text-justify px-2">
                        Настоящий веб-сайт предоставляет пользователям возможность сохранять ссылки (закладки) на
                        сторонние ресурсы.
                        Администрация сайта не публикует, не рекомендует и не распространяет контент, содержащийся по
                        указанным
                        ссылкам.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        2. Ответственность пользователя
                    </div>
                    <div class="text-justify px-2">
                        Пользователь несёт полную ответственность за содержание закладок, которые он сохраняет.
                        Пользователь
                        обязуется
                        не
                        сохранять ссылки, нарушающие законодательство, права третьих лиц или содержащие запрещённый
                        контент.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        3. Ограничение ответственности
                    </div>
                    <div class="text-justify px-2">
                        Администрация сайта не несёт ответственности за содержание сторонних ресурсов, на которые ведут
                        закладки,
                        сохранённые пользователями. Сайт не осуществляет модерацию, проверку или распространение этих
                        ссылок.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        4. Удаление контента
                    </div>
                    <div class="text-justify px-2">
                        В случае получения обоснованных жалоб от третьих лиц или государственных органов, администрация
                        сайта
                        оставляет
                        за
                        собой право удалить соответствующие закладки без предварительного уведомления пользователя.
                    </div>
                </div>

                <div class="mb-1">
                    <div class="font-semibold">
                        5. Передача персональных данных
                    </div>
                    <div class="text-justify px-2">
                        Администрация сайта обязуется соблюдать конфиденциальность персональных данных пользователей и
                        не передавать их третьим лицам, за исключением случаев, предусмотренных законодательством. В
                        частности, данные могут быть переданы компетентным государственным органам по их официальному
                        запросу, в соответствии с действующим законодательством Республики Беларусь.
                    </div>
                </div>

                <div class="hidden mb-1">
                    <div class="font-semibold">
                        6. Юрисдикция
                    </div>
                    <div class="text-justify px-2 ">
                        Настоящее соглашение регулируется законодательством Республики Беларусь. Все споры подлежат
                        рассмотрению в соответствующих судебных органах.
                    </div>
                </div>

            </div>
        </div>
    </template>

    {{-- <div class="tabs tabs-border h-full"> --}}
    {{-- RU --}}
    {{-- <input type="radio" name="user_agreement_tabs" class="tab" aria-label="ru" />
        <div class="tab-content bg-gray-700 border-base-300 p-5 h-full overflow-hidden"> --}}

</div>

{{-- EN --}}
{{-- <input type="radio" name="user_agreement_tabs" class="tab" aria-label="en" checked="checked" />
        <div class="tab-content border-base-300 bg-base-100 p-10">Tab content 2</div>
    </div> --}}
</div>
