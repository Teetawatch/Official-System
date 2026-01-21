@props([
    'user' => null,
    'size' => 'md', // sm, md, lg, xl
    'showTitle' => false,
    'showFrame' => true,
    'class' => '',
])

@php
    $user = $user ?? auth()->user();
    
    // Size classes
    $sizes = [
        'xs' => ['container' => 'w-8 h-8', 'frame' => 'p-0.5', 'icon' => 'w-4 h-4 -bottom-0.5 -right-0.5 text-[8px]', 'title' => 'text-[8px] px-1.5 py-0.5'],
        'sm' => ['container' => 'w-10 h-10', 'frame' => 'p-0.5', 'icon' => 'w-5 h-5 -bottom-1 -right-1 text-xs', 'title' => 'text-[10px] px-2 py-0.5'],
        'md' => ['container' => 'w-12 h-12', 'frame' => 'p-0.5', 'icon' => 'w-6 h-6 -bottom-1 -right-1 text-sm', 'title' => 'text-xs px-2 py-1'],
        'lg' => ['container' => 'w-16 h-16', 'frame' => 'p-1', 'icon' => 'w-7 h-7 -bottom-1 -right-1 text-base', 'title' => 'text-xs px-3 py-1'],
        'xl' => ['container' => 'w-24 h-24', 'frame' => 'p-1', 'icon' => 'w-8 h-8 -bottom-2 -right-2 text-lg', 'title' => 'text-sm px-3 py-1.5'],
        '2xl' => ['container' => 'w-32 h-32', 'frame' => 'p-1.5', 'icon' => 'w-10 h-10 -bottom-2 -right-2 text-xl', 'title' => 'text-sm px-4 py-2'],
    ];
    
    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    
    // Get equipped items
    $frameItem = null;
    $titleItem = null;
    
    if ($user) {
        if (method_exists($user, 'getEquippedFrameItemAttribute')) {
            $frameItem = $user->equipped_frame_item;
            $titleItem = $user->equipped_title_item;
        } elseif (isset($user->equipped_frame) && $user->equipped_frame) {
            $frameItem = \App\Models\RewardItem::find($user->equipped_frame);
        }
        if (isset($user->equipped_title) && $user->equipped_title) {
            $titleItem = \App\Models\RewardItem::find($user->equipped_title);
        }
    }
    
    $frameGradient = $frameItem && isset($frameItem->data['gradient']) 
        ? $frameItem->data['gradient'] 
        : 'from-gray-200 to-gray-300';
    
    $hasFrame = $showFrame && $frameItem;
@endphp

<div class="flex flex-col items-center {{ $class }}">
    {{-- Avatar with Frame --}}
    <div class="relative">
        @if($hasFrame)
            {{-- With Frame --}}
            <div class="{{ $sizeConfig['container'] }} rounded-full bg-gradient-to-br {{ $frameGradient }} {{ $sizeConfig['frame'] }} shadow-lg group-hover:scale-105 transition-transform duration-300">
                <img 
                    src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=2563eb&color=fff' }}" 
                    alt="{{ $user->name ?? 'User' }}" 
                    class="w-full h-full rounded-full object-cover border-2 border-white"
                >
            </div>
            @if(isset($frameItem->data['icon']))
                <div class="absolute {{ $sizeConfig['icon'] }} bg-white rounded-full flex items-center justify-center shadow-md border border-gray-100">
                    <span>{{ $frameItem->data['icon'] }}</span>
                </div>
            @endif
        @else
            {{-- Without Frame --}}
            <div class="{{ $sizeConfig['container'] }} rounded-full overflow-hidden shadow-md ring-2 ring-white group-hover:scale-105 transition-transform duration-300">
                <img 
                    src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=2563eb&color=fff' }}" 
                    alt="{{ $user->name ?? 'User' }}" 
                    class="w-full h-full object-cover"
                >
            </div>
        @endif
    </div>
    
    {{-- Title Badge --}}
    @if($showTitle && $titleItem)
        <div class="mt-1 inline-flex items-center gap-1 {{ $sizeConfig['title'] }} rounded-full bg-gradient-to-r {{ $titleItem->rarity_color }} text-white font-bold shadow-md">
            @if(isset($titleItem->data['emoji']))
                <span>{{ $titleItem->data['emoji'] }}</span>
            @endif
            <span>{{ $titleItem->name }}</span>
        </div>
    @endif
</div>
