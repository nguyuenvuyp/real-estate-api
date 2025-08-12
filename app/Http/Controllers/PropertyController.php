<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    // Danh sách (có filter, sort, pagination)
    public function index(Request $request)
    {
        $query = Property::query();

        // Lọc
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sortBy, $order);

        return response()->json(
            $query->paginate($request->get('per_page', 10))
        );
    }

    // Tạo mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:apartment,house,villa,office,land',
            'status' => 'in:available,sold,rented,pending',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'contact_name' => 'required|string',
            'contact_phone' => 'required|string',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['created_by'] = Auth::id();

        $property = Property::create($data);

        // Upload ảnh nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('property_images', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'image_name' => $image->getClientOriginalName(),
                    'is_primary' => $key === 0
                ]);
            }
        }

        return response()->json($property->load('images'), 201);
    }

    // Xem chi tiết
    public function show(Property $property)
    {
        return response()->json($property->load('images'));
    }

    // Cập nhật
    public function update(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'property_type' => 'in:apartment,house,villa,office,land',
            'status' => 'in:available,sold,rented,pending',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['updated_by'] = Auth::id();
        $property->update($data);

        // Thêm ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'image_name' => $image->getClientOriginalName(),
                ]);
            }
        }

        return response()->json($property->load('images'));
    }

    // Xóa (soft delete)
    public function destroy(Property $property)
    {
        $property->delete();
        return response()->json(['message' => 'Property deleted']);
    }

    // Khôi phục
    public function restore($id)
    {
        $property = Property::withTrashed()->findOrFail($id);
        $property->restore();
        return response()->json(['message' => 'Property restored']);
    }
}
