<header class="mt-3 px-5 sm:mt-10">
    <div class="py-3 dark:border-gray-900 sm:py-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="rounded-full bg-red-500/20 p-4 dark:bg-red-500/20">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6 fill-red-500 text-gray-50 dark:text-gray-950"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>

                <span class="text-dark ml-3 text-2xl font-bold dark:text-white sm:text-3xl">
                    <?php echo e($exception->title()); ?>

                </span>
            </div>

            <div class="flex items-center gap-3 sm:gap-6">
                <?php if (isset($component)) { $__componentOriginald1db52f335a36ef6e9a7182c84fdf72a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1db52f335a36ef6e9a7182c84fdf72a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'laravel-exceptions-renderer::components.theme-swicher','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('laravel-exceptions-renderer::theme-swicher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald1db52f335a36ef6e9a7182c84fdf72a)): ?>
<?php $attributes = $__attributesOriginald1db52f335a36ef6e9a7182c84fdf72a; ?>
<?php unset($__attributesOriginald1db52f335a36ef6e9a7182c84fdf72a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald1db52f335a36ef6e9a7182c84fdf72a)): ?>
<?php $component = $__componentOriginald1db52f335a36ef6e9a7182c84fdf72a; ?>
<?php unset($__componentOriginald1db52f335a36ef6e9a7182c84fdf72a); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</header>
<?php /**PATH D:\xampp1\htdocs\shop4\vendor\laravel\framework\src\Illuminate\Foundation\Providers/../resources/exceptions/renderer/components/navigation.blade.php ENDPATH**/ ?>