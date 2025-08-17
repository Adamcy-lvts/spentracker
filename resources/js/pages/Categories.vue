<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// import { Textarea } from '@/components/ui/textarea'; // Using regular textarea for now
import { Switch } from '@/components/ui/switch';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import InputError from '@/components/InputError.vue';
import { Plus, Edit2, Trash2, Tag } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
  categories: Array<{
    id: number;
    name: string;
    icon: string | null;
    color: string;
    description: string | null;
    is_active: boolean;
    expenses_count: number;
    created_at: string;
    updated_at: string;
  }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Categories',
    href: '/categories',
  },
];

// Dialog states
const addDialogOpen = ref(false);
const editDialogOpen = ref(false);
const deleteDialogOpen = ref(false);
const currentCategory = ref<any>(null);

// Forms
const addForm = useForm({
  name: '',
  icon: '',
  color: '#3B82F6',
  description: '',
  is_active: true,
});

const editForm = useForm({
  name: '',
  icon: '',
  color: '#3B82F6',
  description: '',
  is_active: true,
});

// Predefined colors
const colorOptions = [
  '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
  '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6B7280'
];

// Functions
const openAddDialog = () => {
  addForm.reset();
  addDialogOpen.value = true;
};

const openEditDialog = (category: any) => {
  currentCategory.value = category;
  editForm.name = category.name;
  editForm.icon = category.icon || '';
  editForm.color = category.color;
  editForm.description = category.description || '';
  editForm.is_active = category.is_active;
  editDialogOpen.value = true;
};

const openDeleteDialog = (category: any) => {
  currentCategory.value = category;
  deleteDialogOpen.value = true;
};

const submitAdd = () => {
  addForm.post(route('categories.store'), {
    onSuccess: () => {
      addDialogOpen.value = false;
      toast.success('Category created successfully!');
    },
    onError: () => {
      toast.error('Failed to create category. Please check your inputs.');
    },
  });
};

const submitEdit = () => {
  editForm.put(route('categories.update', currentCategory.value.id), {
    onSuccess: () => {
      editDialogOpen.value = false;
      toast.success('Category updated successfully!');
    },
    onError: () => {
      toast.error('Failed to update category. Please check your inputs.');
    },
  });
};

const deleteCategory = () => {
  const form = useForm({});
  form.delete(route('categories.destroy', currentCategory.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false;
      toast.success('Category deleted successfully!');
    },
    onError: () => {
      toast.error('Failed to delete category.');
    },
  });
};

// Computed
const activeCategories = computed(() => props.categories.filter(cat => cat.is_active));
const inactiveCategories = computed(() => props.categories.filter(cat => !cat.is_active));
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Categories</h1>
                <Button @click="openAddDialog">
                    <Plus class="w-4 h-4 mr-2" />
                    Add Category
                </Button>
            </div>

            <!-- Active Categories -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Tag class="w-5 h-5" />
                        Active Categories ({{ activeCategories.length }})
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="activeCategories.length === 0" class="text-center py-8 text-muted-foreground">
                        <Tag class="mx-auto h-12 w-12 mb-2 opacity-50" />
                        <p>No active categories</p>
                        <p class="text-sm">Create your first category to organize expenses</p>
                    </div>
                    <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div 
                            v-for="category in activeCategories" 
                            :key="category.id"
                            class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div 
                                        class="w-4 h-4 rounded-full" 
                                        :style="{ backgroundColor: category.color }"
                                    ></div>
                                    <h3 class="font-semibold">{{ category.name }}</h3>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Button 
                                        variant="ghost" 
                                        size="sm"
                                        @click="openEditDialog(category)"
                                    >
                                        <Edit2 class="w-4 h-4" />
                                    </Button>
                                    <Button 
                                        variant="ghost" 
                                        size="sm"
                                        @click="openDeleteDialog(category)"
                                        class="text-destructive hover:text-destructive"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                            <p v-if="category.description" class="text-sm text-muted-foreground mb-2">
                                {{ category.description }}
                            </p>
                            <div class="text-xs text-muted-foreground">
                                {{ category.expenses_count }} expense{{ category.expenses_count === 1 ? '' : 's' }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Inactive Categories (if any) -->
            <Card v-if="inactiveCategories.length > 0">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Tag class="w-5 h-5 opacity-50" />
                        Inactive Categories ({{ inactiveCategories.length }})
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div 
                            v-for="category in inactiveCategories" 
                            :key="category.id"
                            class="border rounded-lg p-4 opacity-60"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div 
                                        class="w-4 h-4 rounded-full" 
                                        :style="{ backgroundColor: category.color }"
                                    ></div>
                                    <h3 class="font-semibold">{{ category.name }}</h3>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Button 
                                        variant="ghost" 
                                        size="sm"
                                        @click="openEditDialog(category)"
                                    >
                                        <Edit2 class="w-4 h-4" />
                                    </Button>
                                    <Button 
                                        variant="ghost" 
                                        size="sm"
                                        @click="openDeleteDialog(category)"
                                        class="text-destructive hover:text-destructive"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                            <p v-if="category.description" class="text-sm text-muted-foreground mb-2">
                                {{ category.description }}
                            </p>
                            <div class="text-xs text-muted-foreground">
                                {{ category.expenses_count }} expense{{ category.expenses_count === 1 ? '' : 's' }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Add Category Dialog -->
        <Dialog v-model:open="addDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Add New Category</DialogTitle>
                    <DialogDescription>
                        Create a new category to organize your expenses.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitAdd" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="add-name">Name</Label>
                        <Input
                            id="add-name"
                            v-model="addForm.name"
                            placeholder="e.g., Food & Dining"
                            required
                        />
                        <InputError :message="addForm.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="add-color">Color</Label>
                        <div class="flex items-center gap-2">
                            <Input
                                id="add-color"
                                v-model="addForm.color"
                                type="color"
                                class="w-16 h-10"
                            />
                            <div class="grid grid-cols-5 gap-1">
                                <button
                                    v-for="color in colorOptions"
                                    :key="color"
                                    type="button"
                                    class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                                    :class="addForm.color === color ? 'border-gray-400' : 'border-gray-200'"
                                    :style="{ backgroundColor: color }"
                                    @click="addForm.color = color"
                                ></button>
                            </div>
                        </div>
                        <InputError :message="addForm.errors.color" />
                    </div>

                    <div class="space-y-2">
                        <Label for="add-description">Description (optional)</Label>
                        <textarea
                            id="add-description"
                            v-model="addForm.description"
                            placeholder="Brief description of this category..."
                            rows="2"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        ></textarea>
                        <InputError :message="addForm.errors.description" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <Switch v-model="addForm.is_active" id="add-active" />
                        <Label for="add-active">Active</Label>
                    </div>
                </form>
                <DialogFooter>
                    <Button variant="outline" @click="addDialogOpen = false">Cancel</Button>
                    <Button @click="submitAdd" :disabled="addForm.processing">
                        {{ addForm.processing ? 'Creating...' : 'Create Category' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Edit Category Dialog -->
        <Dialog v-model:open="editDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit Category</DialogTitle>
                    <DialogDescription>
                        Update category details.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="edit-name">Name</Label>
                        <Input
                            id="edit-name"
                            v-model="editForm.name"
                            required
                        />
                        <InputError :message="editForm.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="edit-color">Color</Label>
                        <div class="flex items-center gap-2">
                            <Input
                                id="edit-color"
                                v-model="editForm.color"
                                type="color"
                                class="w-16 h-10"
                            />
                            <div class="grid grid-cols-5 gap-1">
                                <button
                                    v-for="color in colorOptions"
                                    :key="color"
                                    type="button"
                                    class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                                    :class="editForm.color === color ? 'border-gray-400' : 'border-gray-200'"
                                    :style="{ backgroundColor: color }"
                                    @click="editForm.color = color"
                                ></button>
                            </div>
                        </div>
                        <InputError :message="editForm.errors.color" />
                    </div>

                    <div class="space-y-2">
                        <Label for="edit-description">Description (optional)</Label>
                        <textarea
                            id="edit-description"
                            v-model="editForm.description"
                            rows="2"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        ></textarea>
                        <InputError :message="editForm.errors.description" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <Switch v-model="editForm.is_active" id="edit-active" />
                        <Label for="edit-active">Active</Label>
                    </div>
                </form>
                <DialogFooter>
                    <Button variant="outline" @click="editDialogOpen = false">Cancel</Button>
                    <Button @click="submitEdit" :disabled="editForm.processing">
                        {{ editForm.processing ? 'Updating...' : 'Update Category' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Category Dialog -->
        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Category</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ currentCategory?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Cancel</Button>
                    <Button variant="destructive" @click="deleteCategory">
                        Delete Category
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>