import { Controller, Get, Post, Body, Param, Delete, Put } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiResponse } from '@nestjs/swagger';
import { ProductsService } from './products.service';
import { CreateProductDto } from './dto/create-product.dto';
import { Product } from '../entities/product.entity';
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

@ApiTags('products')
@Controller('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}

  @Post()
  @ApiOperation({ summary: 'Create product' })
  @ApiResponse({ status: 201, type: Product })
  @Roles({ roles: ['app-admin', 'products-admin'] })
  create(@Body() createProductDto: CreateProductDto): Promise<Product> {
    return this.productsService.create(createProductDto);
  }

  @Get()
  @ApiOperation({ summary: 'Get all products' })
  @ApiResponse({ status: 200, type: [Product] })
  @Roles({ roles: ['app-admin', 'products-cli'] })
  findAll(): Promise<Product[]> {
    return this.productsService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Get product by id' })
  @ApiResponse({ status: 200, type: Product })
  @Roles({ roles: ['app-admin', 'products-cli'] })
  findOne(@Param('id') id: number): Promise<Product> {
    return this.productsService.findOne(id);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Update product' })
  @ApiResponse({ status: 200, type: Product })
  @Roles({ roles: ['app-admin', 'products-admin'] })
  update(
    @Param('id') id: number,
    @Body() updateProductDto: CreateProductDto,
  ): Promise<Product> {
    return this.productsService.update(id, updateProductDto);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Delete product' })
  @ApiResponse({ status: 200, description: 'Product deleted' })
  @Roles({ roles: ['app-admin', 'products-admin'] })
  remove(@Param('id') id: number): Promise<void> {
    return this.productsService.remove(id);
  }
}