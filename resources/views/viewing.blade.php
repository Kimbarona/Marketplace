<div class="container">
    @foreach ($meal as $meals)
        <h3>{{ $meals->meal_name}}</h3>
        @foreach ($meals->recipe as $res)
            <ul>
                <li>
                    <p>{{ $res->recipe_name}}</p>
                </li>
            </ul>
        @endforeach
        @foreach ( $recipe as $recipes)
            <p>{{ $recipes->price}}</p>
        @endforeach
        <ul>
            <p><strong>{{$meals->cooking->instruction }}</strong></p>
        </ul>
    @endforeach
</div>
