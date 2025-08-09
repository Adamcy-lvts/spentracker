<script setup lang="ts">
import type { DateValue } from "@internationalized/date"
import {
  DateFormatter,
  getLocalTimeZone,
} from "@internationalized/date"
import { CalendarIcon } from "lucide-vue-next"

import { ref } from "vue"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import { Calendar } from "@/components/ui/calendar"
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"

interface Props {
  modelValue?: DateValue
  placeholder?: string
  disabled?: boolean
  class?: string
}

interface Emits {
  (e: 'update:modelValue', value: DateValue | undefined): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Pick a date',
  disabled: false
})

const emit = defineEmits<Emits>()

const df = new DateFormatter("en-US", {
  dateStyle: "long",
})

// Handle model value changes
const handleValueChange = (value: DateValue | undefined) => {
  emit('update:modelValue', value)
}
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-full justify-start text-left font-normal',
          !modelValue && 'text-muted-foreground',
          props.class
        )"
        :disabled="disabled"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ modelValue ? df.format(modelValue.toDate(getLocalTimeZone())) : placeholder }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar 
        :model-value="modelValue" 
        @update:model-value="handleValueChange"
        initial-focus 
      />
    </PopoverContent>
  </Popover>
</template>