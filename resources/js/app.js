import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import mask from '@alpinejs/mask'
import focus from '@alpinejs/focus'
import collapse from '@alpinejs/collapse'

Alpine.plugin(mask)
Alpine.plugin(focus)
Alpine.plugin(collapse)


Livewire.start()
