@if(session('success'))
    <div class="mb-4 flex w-full border-l-6 border-[#34D399] bg-[#34D399] bg-opacity-[15%] px-7 py-8 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30 md:p-9">
        <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-[#34D399]">
            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.2984 0.826822L15.2868 0.811827L15.2741 0.797751C14.9173 0.401867 14.3238 0.400754 13.9657 0.794406L5.91888 9.45376L2.05667 5.2868C1.69856 4.89287 1.10487 4.89389 0.747996 5.28987C0.417335 5.65675 0.417335 6.22337 0.747996 6.59026L0.747959 6.59029L0.752701 6.59541L4.86742 11.0348C5.14445 11.3405 5.52858 11.5 5.89581 11.5C6.29242 11.5 6.65178 11.3355 6.92401 11.035L15.2162 2.11161C15.5833 1.74452 15.576 1.18615 15.2984 0.826822Z" fill="white" stroke="white"/>
            </svg>
        </div>
        <div class="w-full">
            <h5 class="mb-3 text-lg font-semibold text-black dark:text-[#34D399]">
                Success Message
            </h5>
            <p class="text-base leading-relaxed text-body">
                {{ session('success') }}
            </p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 flex w-full border-l-6 border-[#F87171] bg-[#F87171] bg-opacity-[15%] px-7 py-8 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30 md:p-9">
        <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-[#F87171]">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.4917 7.65579L11.106 12.2645C11.2545 12.4128 11.4715 12.5 11.6738 12.5C11.8762 12.5 12.0931 12.4128 12.2416 12.2645C12.5621 11.9445 12.5623 11.4317 12.2423 11.1114C12.2422 11.1113 12.2422 11.1113 12.2422 11.1113C12.242 11.1111 12.2418 11.1109 12.2416 11.1107L7.64539 6.50351C8.19866 5.91221 8.19866 5.01259 7.64539 4.42129L12.2589 -0.249024C12.5794 -0.569038 12.5794 -1.08185 12.2589 -1.40186C11.9384 -1.72187 11.4256 -1.72187 11.1051 -1.40186L6.4917 3.27304C5.89787 2.71976 4.99825 2.71976 4.40442 3.27304L-0.209135 -1.40186C-0.529649 -1.72187 -1.04247 -1.72187 -1.36248 -1.40186C-1.68249 -1.08185 -1.68249 -0.569038 -1.36248 -0.249024L3.25112 4.42129C2.69785 5.01259 2.69785 5.91221 3.25112 6.50351L-1.36248 11.1107C-1.68249 11.4307 -1.68249 11.9435 -1.36248 12.2635C-1.04247 12.5835 -0.529649 12.5835 -0.209135 12.2635L4.40442 7.65579C4.99825 8.20906 5.89787 8.20906 6.4917 7.65579Z" fill="white"/>
            </svg>
        </div>
        <div class="w-full">
            <h5 class="mb-3 text-lg font-semibold text-black dark:text-[#F87171]">
                Error Message
            </h5>
            <p class="text-base leading-relaxed text-body">
                {{ session('error') }}
            </p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-4 flex w-full border-l-6 border-warning bg-warning bg-opacity-[15%] px-7 py-8 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30 md:p-9">
        <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-warning">
            <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.50493 16H17.5023C18.6204 16 19.3413 14.9018 18.8354 13.9735L10.8367 0.770573C10.2852 -0.256858 8.70677 -0.256858 8.15528 0.770573L0.156617 13.9735C-0.334072 14.8998 0.386764 16 1.50493 16ZM10.7585 12.9298C10.7585 13.6155 10.2223 14.1433 9.45583 14.1433C8.6894 14.1433 8.15311 13.6155 8.15311 12.9298V12.9015C8.15311 12.2159 8.6894 11.688 9.45583 11.688C10.2223 11.688 10.7585 12.2159 10.7585 12.9015V12.9298ZM8.75236 4.01062H10.2548C10.6674 4.01062 10.9127 4.33826 10.8671 4.75288L10.2071 10.1186C10.1615 10.5049 9.88572 10.7455 9.50142 10.7455C9.11929 10.7455 8.84138 10.5049 8.79579 10.1186L8.13574 4.75288C8.09449 4.33826 8.33984 4.01062 8.75236 4.01062Z" fill="white"/>
            </svg>
        </div>
        <div class="w-full">
            <h5 class="mb-3 text-lg font-semibold text-[#9D5425]">
                Validation Errors
            </h5>
            <ul class="mb-5 list-disc list-inside text-base leading-relaxed text-body">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif