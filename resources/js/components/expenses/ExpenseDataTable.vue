<script setup lang="ts">
import type {
  ColumnDef,
  ColumnFiltersState,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import {
  FlexRender,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'
import { ChevronDown, Calendar, Trash2, Download, X } from 'lucide-vue-next'
import { ref, computed, onMounted } from 'vue'
import { valueUpdater } from '@/lib/utils'

import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

interface Props {
  columns: ColumnDef<any, any>[]
  data: any[]
}

const props = defineProps<Props>()

const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})

// Monthly filter state
const selectedMonth = ref('')

// Generate month options for the current year
const monthOptions = computed(() => {
  const currentYear = new Date().getFullYear()
  const months = []
  
  for (let i = 0; i < 12; i++) {
    const date = new Date(currentYear, i, 1)
    const monthKey = `${currentYear}-${String(i + 1).padStart(2, '0')}`
    const monthLabel = date.toLocaleDateString('en-US', { 
      month: 'long', 
      year: 'numeric' 
    })
    months.push({ value: monthKey, label: monthLabel })
  }
  
  return [
    { value: 'all', label: 'All Months' },
    ...months
  ]
})

// Set default to current month
onMounted(() => {
  const now = new Date()
  const currentMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
  selectedMonth.value = currentMonth
})

// Filter data based on selected month
const filteredData = computed(() => {
  if (!selectedMonth.value || selectedMonth.value === 'all') return props.data
  
  return props.data.filter(expense => {
    const expenseDate = new Date(expense.date)
    const expenseMonth = `${expenseDate.getFullYear()}-${String(expenseDate.getMonth() + 1).padStart(2, '0')}`
    return expenseMonth === selectedMonth.value
  })
})

// Helper function to format currency
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  }).format(amount)
}

// Summary calculations
const summary = computed(() => {
  const visibleRows = table.getFilteredRowModel().rows
  const total = visibleRows.reduce((sum, row) => {
    const amount = parseFloat(row.original.amount || '0')
    return sum + amount
  }, 0)
  
  return {
    count: visibleRows.length,
    total: formatCurrency(total)
  }
})

// Bulk actions functionality
const selectedRowsCount = computed(() => table.getFilteredSelectedRowModel().rows.length)
const selectedExpenses = computed(() => table.getFilteredSelectedRowModel().rows.map(row => row.original))

const emit = defineEmits<{
  bulkDelete: [expenses: any[]]
  bulkExport: [expenses: any[]]
}>()

const handleBulkDelete = () => {
  if (selectedExpenses.value.length > 0) {
    emit('bulkDelete', selectedExpenses.value)
  }
}

const handleBulkExport = () => {
  if (selectedExpenses.value.length > 0) {
    emit('bulkExport', selectedExpenses.value)
  }
}

const clearSelection = () => {
  table.resetRowSelection()
}

const table = useVueTable({
  get data() { return filteredData.value },
  get columns() { return props.columns },
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
  state: {
    get sorting() { return sorting.value },
    get columnFilters() { return columnFilters.value },
    get columnVisibility() { return columnVisibility.value },
    get rowSelection() { return rowSelection.value },
  },
})
</script>

<template>
  <div class="w-full">
    <!-- Filters and Controls -->
    <div class="flex flex-col gap-4 py-4 md:flex-row md:items-center">
      <!-- Top Row: Month Filter and Column Toggle -->
      <div class="flex items-center gap-4 md:flex-1">
        <!-- Month Filter -->
        <Select v-model="selectedMonth">
          <SelectTrigger class="w-[200px] md:w-[180px]">
            <Calendar class="mr-2 h-4 w-4" />
            <SelectValue placeholder="Select month" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="option in monthOptions"
              :key="option.value"
              :value="option.value"
            >
              {{ option.label }}
            </SelectItem>
          </SelectContent>
        </Select>
        
        <!-- Column Visibility Toggle -->
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="outline" class="md:ml-auto">
              Columns <ChevronDown class="ml-2 h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuCheckboxItem
              v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
              :key="column.id"
              class="capitalize"
              :model-value="column.getIsVisible()"
              @update:model-value="(value) => {
                column.toggleVisibility(!!value)
              }"
            >
              {{ column.id }}
            </DropdownMenuCheckboxItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
      
      <!-- Bottom Row: Description Filter (Full Width on Mobile) -->
      <div class="w-full md:max-w-sm">
        <Input
          class="w-full"
          placeholder="Filter by description..."
          :model-value="String(table.getColumn('description')?.getFilterValue() ?? '')"
          @update:model-value="table.getColumn('description')?.setFilterValue($event)"
        />
      </div>
    </div>

    <!-- Bulk Actions Toolbar -->
    <div v-if="selectedRowsCount > 0" class="flex items-center justify-between p-4 bg-muted/50 border rounded-lg mb-4">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium">{{ selectedRowsCount }} expense(s) selected</span>
        <Button variant="ghost" size="sm" @click="clearSelection">
          <X class="h-4 w-4 mr-1" />
          Clear
        </Button>
      </div>
      <div class="flex items-center gap-2">
        <Button variant="outline" size="sm" @click="handleBulkExport">
          <Download class="h-4 w-4 mr-1" />
          Export Selected
        </Button>
        <Button variant="destructive" size="sm" @click="handleBulkDelete">
          <Trash2 class="h-4 w-4 mr-1" />
          Delete Selected
        </Button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender 
                v-if="!header.isPlaceholder" 
                :render="header.column.columnDef.header" 
                :props="header.getContext()" 
              />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="table.getRowModel().rows?.length">
            <TableRow 
              v-for="row in table.getRowModel().rows" 
              :key="row.id"
              :data-state="row.getIsSelected() && 'selected'"
              class="hover:bg-muted/20 transition-colors duration-200"
            >
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </TableCell>
            </TableRow>
          </template>

          <TableRow v-else>
            <TableCell
              :colspan="columns.length"
              class="h-24 text-center"
            >
              <div class="flex flex-col items-center justify-center py-12">
                <div class="w-12 h-12 mb-4 rounded-full bg-muted flex items-center justify-center">
                  <span class="text-muted-foreground">📝</span>
                </div>
                <h3 class="text-sm font-medium text-foreground mb-1">No expenses yet</h3>
                <p class="text-sm text-muted-foreground">Add your first expense to get started!</p>
              </div>
            </TableCell>
          </TableRow>
          <!-- Summary Row -->
          <TableRow v-if="table.getRowModel().rows?.length" class="border-t-2 border-primary/20 bg-muted/10 hover:bg-muted/20 font-semibold">
            <!-- Select Column (empty for summary) -->
            <TableCell class="py-4"></TableCell>
            <!-- Description Column with Total Label -->
            <TableCell class="py-4">
              <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                  <span class="text-xs font-bold text-primary">Σ</span>
                </div>
                <div class="font-bold text-primary">Total ({{ summary.count }} expenses)</div>
              </div>
            </TableCell>
            <!-- Amount Column with Total -->
            <TableCell class="py-4">
              <div class="font-bold text-lg text-primary">{{ summary.total }}</div>
            </TableCell>
            <!-- Date Column (empty for summary) -->
            <TableCell class="py-4"></TableCell>
            <!-- Actions Column (empty for summary) -->
            <TableCell class="py-4"></TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-end space-x-2 py-4">
      <div class="flex-1 text-sm text-muted-foreground">
        {{ table.getFilteredSelectedRowModel().rows.length }} of
        {{ table.getFilteredRowModel().rows.length }} row(s) selected.
      </div>
      <div class="space-x-2">
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanPreviousPage()"
          @click="table.previousPage()"
        >
          Previous
        </Button>
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanNextPage()"
          @click="table.nextPage()"
        >
          Next
        </Button>
      </div>
    </div>
  </div>
</template>