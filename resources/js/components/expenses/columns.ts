import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'
import { ArrowUpDown, MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

// Define the Expense interface
export interface Expense {
  id: number
  description: string
  amount: string
  date: string
  category_id: number | null
  category?: {
    id: number
    name: string
    icon: string
    color: string
  }
  user_id: number
  created_at: string
  updated_at: string
}

// Helper function to format date
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

// Helper function to format currency to Naira
const formatCurrency = (amount: string) => {
  const num = parseFloat(amount)
  return new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  }).format(num)
}

// Define columns
export const columns: ColumnDef<Expense>[] = [
  {
    id: 'select',
    header: ({ table }) => h(Checkbox, {
      'modelValue': table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
      'onUpdate:modelValue': (value: boolean) => table.toggleAllPageRowsSelected(!!value),
      'ariaLabel': 'Select all',
    }),
    cell: ({ row }) => h(Checkbox, {
      'modelValue': row.getIsSelected(),
      'onUpdate:modelValue': (value: boolean) => row.toggleSelected(!!value),
      'ariaLabel': 'Select row',
    }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'description',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Description', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
      return h('div', { class: 'flex items-center' }, [
        h('div', { class: 'w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center mr-3' }, 
          h('span', { class: 'text-xs font-medium text-primary' }, '₦')
        ),
        h('div', { class: 'font-medium' }, row.getValue('description'))
      ])
    },
  },
  {
    accessorKey: 'amount',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Amount', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
      const amount = row.getValue('amount') as string
      return h('div', { class: 'font-semibold text-foreground' }, formatCurrency(amount))
    },
  },
  {
    accessorKey: 'category',
    header: 'Category',
    cell: ({ row }) => {
      const category = row.original.category
      if (!category) {
        return h('div', { class: 'text-muted-foreground text-sm' }, 'Uncategorized')
      }
      return h('div', { class: 'flex items-center gap-2' }, [
        h('div', { 
          class: 'w-3 h-3 rounded-full', 
          style: { backgroundColor: category.color } 
        }),
        h('span', { class: 'text-sm' }, category.name)
      ])
    },
  },
  {
    accessorKey: 'date',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Date', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
      const date = row.getValue('date') as string
      return h('div', { class: 'text-muted-foreground' }, formatDate(date))
    },
  },
  {
    id: 'actions',
    enableHiding: false,
    cell: ({ row }) => {
      const expense = row.original
      return h('div', { class: 'relative' }, 
        h(DropdownMenu, {}, {
          default: () => [
            h(DropdownMenuTrigger, { asChild: true }, {
              default: () => h(Button, {
                variant: 'ghost',
                class: 'h-8 w-8 p-0'
              }, {
                default: () => [
                  h('span', { class: 'sr-only' }, 'Open menu'),
                  h(MoreHorizontal, { class: 'h-4 w-4' })
                ]
              })
            }),
            h(DropdownMenuContent, { align: 'end' }, {
              default: () => [
                h(DropdownMenuItem, {
                  onClick: () => {
                    // Emit edit event - we'll handle this in the parent component
                    const event = new CustomEvent('editExpense', { detail: expense })
                    document.dispatchEvent(event)
                  },
                  class: 'cursor-pointer'
                }, () => 'Edit'),
                h(DropdownMenuItem, {
                  onClick: () => {
                    // Emit delete event - we'll handle this in the parent component
                    const event = new CustomEvent('deleteExpense', { detail: expense })
                    document.dispatchEvent(event)
                  },
                  class: 'cursor-pointer text-destructive focus:text-destructive'
                }, () => 'Delete')
              ]
            })
          ]
        })
      )
    },
  },
]