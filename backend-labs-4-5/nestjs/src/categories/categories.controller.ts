import { Controller, Get, Post, Body, Param, Delete, Put } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiResponse } from '@nestjs/swagger';
import { CategoriesService } from './categories.service';
import { CreateCategoryDto } from './dto/create-category.dto';
import { Category } from '../entities/category.entity';
import { 
  KeycloakConnectModule,
  ResourceGuard,
  RoleGuard,
  AuthGuard,
  Resource,
  Roles,
  Public,
  Unprotected
} from 'nest-keycloak-connect';
import { APP_GUARD } from '@nestjs/core';

@ApiTags('categories')
@Controller('categories')
export class CategoriesController {
  constructor(private readonly categoriesService: CategoriesService) {}

  @Post()
  @ApiOperation({ summary: 'Create category' })
  @ApiResponse({ status: 201, type: Category })
  @Roles({ roles: ['app-admin', 'categories-admin'] })
  create(@Body() createCategoryDto: CreateCategoryDto): Promise<Category> {
    return this.categoriesService.create(createCategoryDto);
  }

  @Get()
  @ApiOperation({ summary: 'Get all categories' })
  @ApiResponse({ status: 200, type: [Category] })
  @Roles({ roles: ['app-admin', 'categories-cli'] })
  findAll(): Promise<Category[]> {
    return this.categoriesService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Get category by id' })
  @ApiResponse({ status: 200, type: Category })
  @Roles({ roles: ['app-admin', 'categories-cli'] })
  findOne(@Param('id') id: number): Promise<Category> {
    return this.categoriesService.findOne(id);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Update category' })
  @ApiResponse({ status: 200, type: Category })
  @Roles({ roles: ['app-admin', 'categories-admin'] })
  update(
    @Param('id') id: number,
    @Body() updateCategoryDto: CreateCategoryDto,
  ): Promise<Category> {
    return this.categoriesService.update(id, updateCategoryDto);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Delete category' })
  @ApiResponse({ status: 200, description: 'Category deleted' })
  @Roles({ roles: ['app-admin', 'categories-admin'] })
  remove(@Param('id') id: number): Promise<void> {
    return this.categoriesService.remove(id);
  }
} 